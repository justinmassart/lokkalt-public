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
 * @property string $variant_id
 * @property string $image_id
 * @property-read \App\Models\Image $image
 * @property-read \App\Models\Variant $variant
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantImage whereVariantId($value)
 * @mixin \Eloquent
 */
class VariantImage extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
