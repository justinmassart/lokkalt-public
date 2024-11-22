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
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $franchise_id
 * @property string $user_id
 * @property-read \App\Models\Franchise $franchise
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseRegistrationToken whereUserId($value)
 * @mixin \Eloquent
 */
class FranchiseRegistrationToken extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
