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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\Variant|null $variant
 * @method static \Database\Factories\PriceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Price extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_prices', 'article_id', 'price_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'price_variants', 'price_id', 'variant_id');
    }
}
