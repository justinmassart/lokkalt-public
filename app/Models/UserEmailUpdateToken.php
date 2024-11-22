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
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailUpdateToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserEmailUpdateToken extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
