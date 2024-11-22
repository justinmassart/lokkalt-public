<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * 
 *
 * @property string $id
 * @property string|null $verified_at
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $VAT
 * @property string|null $bank_account
 * @property string $country
 * @property string $city
 * @property string $postal_code
 * @property string $address
 * @property string|null $stripe_customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FranchiseOwner|null $franchiseOwner
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FranchisePack> $packs
 * @property-read int|null $packs_count
 * @property-read \App\Models\FranchiseRegistrationToken|null $registrationToken
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shop> $shops
 * @property-read int|null $shops_count
 * @property-read \App\Models\FranchiseSubscription|null $subscription
 * @method static \Database\Factories\FranchiseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereStripeCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereVAT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Franchise whereVerifiedAt($value)
 * @mixin \Eloquent
 */
class Franchise extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function franchiseOwner(): HasOne
    {
        return $this->hasOne(FranchiseOwner::class);
    }

    public function owner(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            FranchiseOwner::class,
            'franchise_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function hasOwner(User $user): bool
    {
        return $this->owner->id === $user->id;
    }

    public function registrationToken(): HasOne
    {
        return $this->hasOne(FranchiseRegistrationToken::class);
    }

    public function packs(): HasMany
    {
        return $this->hasMany(FranchisePack::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(FranchiseSubscription::class);
    }

    public function hasPack(string $name)
    {
        return $this->packs()->where('is_active', true)->whereHas('pack', function ($query) use ($name) {
            $query->where('name', $name);
        })->exists();
    }

    public function flag(): string
    {
        return (string) preg_replace_callback(
            '/./',
            static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),
            $this->country
        );
    }
}
