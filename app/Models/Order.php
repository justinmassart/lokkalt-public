<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * 
 *
 * @property string $id
 * @property string $status
 * @property string $reference
 * @property string $sub_total
 * @property string $total
 * @property string $payment_id
 * @property string $stripe_secret
 * @property int $has_been_refunded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property string $shop_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopArticle> $shopArticles
 * @property-read int|null $shop_articles_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasBeenRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStripeSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'reference';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shopArticles(): HasManyThrough
    {
        return $this->hasManyThrough(
            ShopArticle::class,
            OrderItem::class,
            'order_id',
            'id',
            'id',
            'shop_article_id',
        );
    }

    public function articles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Article::class,
            OrderItem::class,
            'order_id',
            'id',
            'id',
            'article_id'
        );
    }

    public function variants(): HasManyThrough
    {
        return $this->hasManyThrough(
            Variant::class,
            OrderItem::class,
            'order_id',
            'id',
            'id',
            'variant_id'
        );
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
