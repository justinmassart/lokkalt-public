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
 * @property string $user_id
 * @property-read \App\Models\User|null $owner
 * @property-read \App\Models\Shop $shop
 * @method static \Database\Factories\ShopOwnerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopOwner whereUserId($value)
 * @mixin \Eloquent
 */
class ShopOwner extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
