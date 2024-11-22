<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $id
 * @property string $type
 * @property string $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserNotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereUserId($value)
 * @mixin \Eloquent
 */
class UserNotification extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
