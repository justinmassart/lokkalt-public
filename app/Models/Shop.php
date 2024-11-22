<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;
use Livewire\WithFileUploads;

/**
 * 
 *
 * @property string $id
 * @property int $is_active
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $description
 * @property string $country
 * @property string $city
 * @property int $postal_code
 * @property string $address
 * @property string|null $VAT
 * @property string $bank_account
 * @property array|null $opening_hours
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $franchise_id
 * @property-read Collection<int, \App\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read Collection<int, \App\Models\User> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Franchise $franchise
 * @property-read \App\Models\ShopGlobalScore|null $global_score
 * @property-read Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read Collection<int, \App\Models\OrderItem> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, \App\Models\User> $owners
 * @property-read int|null $owners_count
 * @property-read Collection<int, \App\Models\ShopImage> $pivotImages
 * @property-read int|null $pivot_images_count
 * @property-read \App\Models\ShopRegistrationToken|null $registrationTokens
 * @property-read Collection<int, \App\Models\ShopScore> $scores
 * @property-read int|null $scores_count
 * @property-read Collection<int, \App\Models\ShopArticle> $shopArticles
 * @property-read int|null $shop_articles_count
 * @property-read Collection<int, \App\Models\ShopOwner> $shopOwners
 * @property-read int|null $shop_owners_count
 * @property-read Collection<int, \App\Models\ShopEmployee> $shop_employees
 * @property-read int|null $shop_employees_count
 * @property-read Collection<int, \App\Models\ShopImage> $shop_images
 * @property-read int|null $shop_images_count
 * @property-read Collection<int, \App\Models\UserFavouriteShop> $userFavourites
 * @property-read int|null $user_favourites_count
 * @property-read Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\ShopFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereOpeningHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereVAT($value)
 * @mixin \Eloquent
 */
class Shop extends Model
{
    use HasFactory, HasUuids, WithFileUploads, Searchable;

    protected $guarded = [];

    protected $casts = [
        'opening_hours' => 'array',
        'socials' => 'array',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function searchableAs(): string
    {
        return 'shops_index';
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
        ];
    }

    public function shopArticles(): HasMany
    {
        return $this->hasMany(ShopArticle::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'shop_articles',
            'shop_id',
            'article_id'
        )->distinct();
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            Variant::class,
            'shop_articles',
            'shop_id',
            'variant_id'
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ShopScore::class);
    }

    public function userFavourites(): HasMany
    {
        return $this->hasMany(UserFavouriteShop::class);
    }

    public function global_score(): HasOne
    {
        return $this->hasOne(ShopGlobalScore::class);
    }

    public function owners(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ShopOwner::class, 'shop_id', 'id', 'id', 'user_id');
    }

    public function shopOwners(): HasMany
    {
        return $this->hasMany(ShopOwner::class);
    }

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(
            Image::class,
            ShopImage::class,
            'shop_id',
            'id',
            'id',
            'image_id'
        );
    }

    public function shop_images(): HasMany
    {
        return $this->hasMany(ShopImage::class);
    }

    public function pivotImages(): HasMany
    {
        return $this->hasMany(ShopImage::class);
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ShopEmployee::class,
            'shop_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function shop_employees(): HasMany
    {
        return $this->hasMany(ShopEmployee::class);
    }

    public function flag(): string
    {
        return (string) preg_replace_callback(
            '/./',
            static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),
            $this->country
        );
    }

    public function doesOwnArticle(Article $article)
    {
        return $this->articles()->where('article_id', $article->id)->exists();
    }

    public function registrationTokens(): HasOne
    {
        return $this->hasOne(ShopRegistrationToken::class);
    }

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function isInUserCountry(): bool
    {
        $country = explode('-', app()->getLocale())[1];

        return $this->country === $country;
    }
}
