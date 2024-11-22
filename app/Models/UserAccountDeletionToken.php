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
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAccountDeletionToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserAccountDeletionToken extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
