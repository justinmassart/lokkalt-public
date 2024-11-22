<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property string $id
 * @property int $quantity
 * @property string $status
 * @property int $limited_stock_below
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_article_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockOperation> $operations
 * @property-read int|null $operations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\ShopArticle $shopArticle
 * @method static \Database\Factories\StockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereLimitedStockBelow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereShopArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function orders(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'shop_article_id',
            'shop_article_id'
        );
    }

    public function operations(): HasMany
    {
        return $this->hasMany(StockOperation::class);
    }

    public function shopArticle(): BelongsTo
    {
        return $this->belongsTo(ShopArticle::class);
    }
}
