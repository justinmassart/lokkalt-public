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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $pack_id
 * @property string $shop_id
 * @property-read \App\Models\Pack $pack
 * @property-read \App\Models\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack wherePackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopPack extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
}
