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
 * @property string $shop_id
 * @property string $image_id
 * @property-read \App\Models\Image $image
 * @property-read \App\Models\Shop $shop
 * @method static \Database\Factories\ShopImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopImage extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
