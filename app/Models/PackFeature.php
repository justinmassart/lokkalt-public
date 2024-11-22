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
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $pack_id
 * @property-read \App\Models\Pack $pack
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature wherePackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackFeature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PackFeature extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
}
