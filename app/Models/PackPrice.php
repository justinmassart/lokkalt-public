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
 * @property string $country
 * @property string $stripe_id
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $pack_id
 * @property-read \App\Models\Pack $pack
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice wherePackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PackPrice extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
}
