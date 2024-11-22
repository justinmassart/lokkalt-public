<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * 
 *
 * @property string $id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $franchise_id
 * @property string $pack_id
 * @property-read \App\Models\Franchise $franchise
 * @property-read \App\Models\Pack $pack
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack wherePackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchisePack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FranchisePack extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
}
