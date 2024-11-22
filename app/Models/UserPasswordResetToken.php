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
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPasswordResetToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserPasswordResetToken extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
