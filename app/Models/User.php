<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property string $id
 * @property string $role
 * @property string $firstname
 * @property string $lastname
 * @property string $full_name
 * @property string $slug
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $country
 * @property string|null $phone
 * @property string|null $address
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property-read \App\Models\UserAccountDeletionToken|null $accountDeletionToken
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleScore> $articlesScores
 * @property-read int|null $articles_scores_count
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\UserEmailUpdateToken|null $emailUpdateToken
 * @property-read \App\Models\EmailVerificationToken|null $emailVerificationToken
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $favouriteArticles
 * @property-read int|null $favourite_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shop> $favouriteShops
 * @property-read int|null $favourite_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Franchise> $franchises
 * @property-read int|null $franchises_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FranchiseRegistrationToken> $franchisesRegistrationToken
 * @property-read int|null $franchises_registration_token_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLikingArticleScore> $likeArticleScore
 * @property-read int|null $like_article_score_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLikingShopScore> $likeShopScore
 * @property-read int|null $like_shop_score_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\UserPasswordResetToken|null $passwordResetToken
 * @property-read \App\Models\UserPhoneUpdateToken|null $phoneUpdateToken
 * @property-read \App\Models\UserPreference|null $preference
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleScoreAnswer> $scoreAnswers
 * @property-read int|null $score_answers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopOwner> $shopOwners
 * @property-read int|null $shop_owners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopRegistrationToken> $shopRegistrationTokens
 * @property-read int|null $shop_registration_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shop> $shops
 * @property-read int|null $shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopScore> $shopsScore
 * @property-read int|null $shops_score_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserFavouriteArticle> $user_favourite_articles
 * @property-read int|null $user_favourite_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserFavouriteShop> $user_favourite_shops
 * @property-read int|null $user_favourite_shops_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePmLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePmType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements CanResetPassword, FilamentUser, HasName, MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, HasUuids, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function emailVerificationToken(): HasOne
    {
        return $this->hasOne(EmailVerificationToken::class);
    }

    public function passwordResetToken(): HasOne
    {
        return $this->hasOne(UserPasswordResetToken::class);
    }

    public function accountDeletionToken(): HasOne
    {
        return $this->hasOne(UserAccountDeletionToken::class);
    }

    public function emailUpdateToken(): HasOne
    {
        return $this->hasOne(UserEmailUpdateToken::class);
    }

    public function phoneUpdateToken(): HasOne
    {
        return $this->hasOne(UserPhoneUpdateToken::class);
    }

    public function articlesScores(): HasMany
    {
        return $this->hasMany(ArticleScore::class);
    }

    public function shopsScore(): HasMany
    {
        return $this->hasMany(ShopScore::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function favouriteArticles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Article::class,
            UserFavouriteArticle::class,
            'user_id',
            'id',
            'id',
            'article_id'
        );
    }

    public function user_favourite_articles(): HasMany
    {
        return $this->hasMany(UserFavouriteArticle::class);
    }

    public function favouriteShops(): HasManyThrough
    {
        return $this->hasManyThrough(
            Shop::class,
            UserFavouriteShop::class,
            'user_id',
            'id',
            'id',
            'shop_id'
        );
    }

    public function user_favourite_shops(): HasMany
    {
        return $this->hasMany(UserFavouriteShop::class);
    }

    public function likeArticleScore(): HasMany
    {
        return $this->hasMany(UserLikingArticleScore::class);
    }

    public function likeShopScore(): HasMany
    {
        return $this->hasMany(UserLikingShopScore::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function articlesInCart()
    {
        return $this->cart->articles;
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    // AUTHENTICATION

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    public function shops(): HasManyThrough
    {
        return $this->hasManyThrough(
            Shop::class,
            ShopOwner::class,
            'user_id',
            'id',
            'id',
            'shop_id'
        );
    }

    public function shopOwners(): HasMany
    {
        return $this->hasMany(ShopOwner::class);
    }

    public function canAccessShop(Shop $shop)
    {
        return $this->shops()->where('shop_id', $shop->id)->exists();
    }

    public function canAccessFranchise(Franchise $franchise)
    {
        return $this->franchises()->where('franchise_id', $franchise->id)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'dashboard') {
            return $this->hasRole('seller') && $this->hasVerifiedEmail();
        }

        if ($panel->getId() === 'admin') {
            return $this->email === 'admin@lokkalt.com';
        }

        return false;
    }

    public function getFilamentName(): string
    {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function isSeller()
    {
        return $this->role === 'seller';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function scoreAnswers(): HasMany
    {
        return $this->hasMany(ArticleScoreAnswer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function doesHaveOrder($ref): bool
    {
        return $this->orders()->where('reference', $ref)->exists();
    }

    public function hash()
    {
        $appKey = config('app.key');

        return hash('sha256', $this->id . $appKey);
    }

    public function shopRegistrationTokens(): HasMany
    {
        return $this->hasMany(ShopRegistrationToken::class);
    }

    public function franchises(): HasManyThrough
    {
        return $this->hasManyThrough(
            Franchise::class,
            FranchiseOwner::class,
            'user_id',
            'id',
            'id',
            'franchise_id'
        );
    }

    public function franchisesRegistrationToken(): HasMany
    {
        return $this->hasMany(FranchiseRegistrationToken::class);
    }
}
