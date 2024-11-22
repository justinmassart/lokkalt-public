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
 * @property int $has_paid
 * @property string $customer_id
 * @property string $subscription_id
 * @property string $payment_id
 * @property string $stripe_status
 * @property float $stripe_price
 * @property string|null $trial_ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $franchise_id
 * @property-read \App\Models\Franchise $franchise
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereHasPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereStripePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereStripeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FranchiseSubscription extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }
}
