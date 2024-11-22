<?php

namespace App\Models;

use App\Livewire\Cart\CartIcon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * 
 *
 * @property string $id
 * @property string $hashed_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_id
 * @property string $article_id
 * @property string $variant_id
 * @property-read \App\Models\Article $article
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleScore> $scores
 * @property-read int|null $scores_count
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\Stock|null $stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockOperation> $stockOperations
 * @property-read int|null $stock_operations_count
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\ShopArticleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereHashedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopArticle whereVariantId($value)
 * @mixin \Eloquent
 */
class ShopArticle extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->hashed_id = hash('sha256', $model->id);
        });
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reservedOrders(): int
    {
        return $this->hasMany(OrderItem::class)
            ->whereIn('status', ['waiting', 'started'])->sum('quantity');
    }

    public function stockOperations(): HasManyThrough
    {
        return $this->hasManyThrough(
            StockOperation::class,
            Stock::class,
            'shop_article_id',
            'stock_id',
            'id',
            'id'
        );
    }

    public function scores(): HasManyThrough
    {
        return $this->hasManyThrough(
            ArticleScore::class,
            Article::class,
            'id',
            'article_id',
            'article_id',
            'id'
        );
    }

    public function addArticleToCart(): void
    {
        $shopArticle = $this;
        $variant = $this->variant;
        $article = $this->article;
        $shop = $this->shop;

        if (!$article || !$variant || !$article->doesHaveVariant($variant) || !$shop || !$shopArticle) {
            return;
        }

        if (!auth()->user()) {
            $cart = session()->get('guestCart');

            if (!$cart) {
                $cart = [];
            }

            $cart[$shopArticle->id] = [
                'quantity' => 1,
            ];

            session()->put('guestCart', $cart);

            return;
        }

        $cart = Cart::whereUserId(auth()->user()->id)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
            ]);
        }

        $cart->article_carts()->create(
            [
                'shop_article_id' => $shopArticle->id,
                'quantity' => 1,
            ]
        );
    }

    public function removeArticleFromCart(): void
    {
        $shopArticle = $this;
        $variant = $this->variant;
        $article = $this->article;
        $shop = $this->shop;

        if (!$article || !$variant || !$article->doesHaveVariant($variant) || !$shop || !$shopArticle) {
            return;
        }

        if (!auth()->user()) {
            $cart = session()->get('guestCart');

            unset($cart[$shopArticle->id]);

            session()->put('guestCart', $cart);

            return;
        }

        $cart = Cart::whereUserId(auth()->user()->id)->first();

        $cart->article_carts()->firstWhere('shop_article_id', $shopArticle->id)->delete();

        if (auth()->user()->cart->article_carts()->count() === 0) {
            auth()->user()->cart->delete();
        }
    }
}
