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
 * @property string $price
 * @property string $currency
 * @property string $per
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $variant_id
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\VariantPriceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice wherePer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantPrice whereVariantId($value)
 * @mixin \Eloquent
 */
class VariantPrice extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
