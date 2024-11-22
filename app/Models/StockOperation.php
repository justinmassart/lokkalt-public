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
 * @property int $stock_before
 * @property int $stock_after
 * @property string|null $operation
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $stock_id
 * @property string $user_id
 * @property-read \App\Models\Stock $stock
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereStockAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereStockBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOperation whereUserId($value)
 * @mixin \Eloquent
 */
class StockOperation extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
