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
 * @property string $user_id
 * @property string $shop_id
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteShop whereUserId($value)
 * @mixin \Eloquent
 */
class UserFavouriteShop extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
