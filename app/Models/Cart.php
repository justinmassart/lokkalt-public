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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleCart> $article_carts
 * @property-read int|null $article_carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopArticle> $shopArticles
 * @property-read int|null $shop_articles_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shopArticles(): HasManyThrough
    {
        return $this->hasManyThrough(
            ShopArticle::class,
            ArticleCart::class,
            'cart_id',
            'id',
            'id',
            'shop_article_id'
        );
    }

    public function article_carts(): HasMany
    {
        return $this->hasMany(ArticleCart::class);
    }
}
