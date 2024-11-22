<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property string $id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_article_id
 * @property string $cart_id
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\ShopArticle $shopArticle
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\ArticleCartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereShopArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleCart extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shopArticle(): BelongsTo
    {
        return $this->belongsTo(ShopArticle::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'carts', 'user_id', 'id');
    }
}
