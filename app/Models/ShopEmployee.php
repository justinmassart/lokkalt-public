<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
 * @property-read \App\Models\User|null $employee
 * @property-read \App\Models\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopEmployee whereUserId($value)
 * @mixin \Eloquent
 */
class ShopEmployee extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
