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
 * @property string|null $score
 * @property int|null $total_votes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_id
 * @property-read \App\Models\Shop $shop
 * @method static \Database\Factories\ShopGlobalScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereTotalVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopGlobalScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopGlobalScore extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
