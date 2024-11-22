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
 * @property string $liking
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property string $shop_score_id
 * @property-read \App\Models\ShopScore $shopScore
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereLiking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereShopScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingShopScore whereUserId($value)
 * @mixin \Eloquent
 */
class UserLikingShopScore extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shopScore(): BelongsTo
    {
        return $this->belongsTo(ShopScore::class);
    }
}
