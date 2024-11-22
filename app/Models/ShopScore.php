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
 * @property string $score
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shop_id
 * @property string $user_id
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ShopScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopScore whereUserId($value)
 * @mixin \Eloquent
 */
class ShopScore extends Model
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
