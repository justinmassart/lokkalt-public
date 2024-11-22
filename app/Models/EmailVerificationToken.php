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
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerificationToken whereUserId($value)
 * @mixin \Eloquent
 */
class EmailVerificationToken extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
