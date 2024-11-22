<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property string $id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPhoneUpdateToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserPhoneUpdateToken extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
