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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $franchise_id
 * @property string $user_id
 * @property-read \App\Models\Franchise $franchise
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseOwner whereUserId($value)
 * @mixin \Eloquent
 */
class FranchiseOwner extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }
}
