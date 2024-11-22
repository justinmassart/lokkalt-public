<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property string $id
 * @property int $is_active
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackFeature> $features
 * @property-read int|null $features_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackPrice> $prices
 * @property-read int|null $prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pack query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pack whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pack whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pack extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function prices(): HasMany
    {
        return $this->hasMany(PackPrice::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(PackFeature::class);
    }
}
