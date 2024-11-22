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
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_id
 * @property string $user_id
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopRegistrationToken whereUserId($value)
 * @mixin \Eloquent
 */
class ShopRegistrationToken extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
