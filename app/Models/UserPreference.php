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
 * @property string $preferences
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserPreferenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereUserId($value)
 * @mixin \Eloquent
 */
class UserPreference extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
