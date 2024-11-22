<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
use Spatie\Tags\HasTags;

/**
 * 
 *
 * @property string $id
 * @property string $reference
 * @property int $is_active
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property array|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $category_id
 * @property string $sub_category_id
 * @property-read Collection<int, \App\Models\ArticleCart> $articleCarts
 * @property-read int|null $article_carts_count
 * @property-read \App\Models\ArticleCategory|null $article_categories
 * @property-read Collection<int, \App\Models\ArticleImage> $article_images
 * @property-read int|null $article_images_count
 * @property-read Collection<int, \App\Models\ArticleQuestion> $article_questions
 * @property-read int|null $article_questions_count
 * @property-read Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\Category $category
 * @property-read Collection<int, \App\Models\User> $favourites_users
 * @property-read int|null $favourites_users_count
 * @property-read \App\Models\ArticleGlobalScore|null $global_score
 * @property-read Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read Collection<int, \App\Models\ArticleImage> $pivotImages
 * @property-read int|null $pivot_images_count
 * @property-read Collection<int, \App\Models\VariantPrice> $prices
 * @property-read int|null $prices_count
 * @property-read Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read Collection<int, \App\Models\ArticleScore> $scores
 * @property-read int|null $scores_count
 * @property Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read Collection<int, \App\Models\ShopArticle> $shopArticles
 * @property-read int|null $shop_articles_count
 * @property-read Collection<int, \App\Models\Shop> $shops
 * @property-read int|null $shops_count
 * @property-read Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\SubCategory $sub_category
 * @property-read int|null $tags_count
 * @property-read Collection<int, \App\Models\UserFavouriteArticle> $user_favourite_articles
 * @property-read int|null $user_favourite_articles_count
 * @property-read Collection<int, \App\Models\VariantImage> $variantImages
 * @property-read int|null $variant_images_count
 * @property-read Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\ArticleFactory factory($count = null, $state = [])
 * @method static Builder|Article newModelQuery()
 * @method static Builder|Article newQuery()
 * @method static Builder|Article query()
 * @method static Builder|Article whereCategoryId($value)
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereDescription($value)
 * @method static Builder|Article whereDetails($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article whereIsActive($value)
 * @method static Builder|Article whereName($value)
 * @method static Builder|Article whereReference($value)
 * @method static Builder|Article whereSlug($value)
 * @method static Builder|Article whereSubCategoryId($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @method static Builder|Article withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Article withAllTagsOfAnyType($tags)
 * @method static Builder|Article withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Article withAnyTagsOfAnyType($tags)
 * @method static Builder|Article withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory, HasTags, HasUuids, WithFileUploads, Searchable;

    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
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
        return 'articles_index';
    }

    public function toSearchableArray()
    {
        return [
            'reference' => $this->reference,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }

    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('shopArticles');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function article_categories(): HasOne
    {
        return $this->hasOne(ArticleCategory::class);
    }

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(
            Image::class,
            ArticleImage::class,
            'article_id',
            'id',
            'id',
            'image_id'
        );
    }

    public function article_images(): HasMany
    {
        return $this->hasMany(ArticleImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function variant()
    {
        return $this->variants()->where('is_visible', true)->whereHas('stock', function ($query) {
            $query->where('status', '!=', 'out');
        })->first();
    }

    public function variantImages()
    {
        return $this->hasManyThrough(
            VariantImage::class,
            Variant::class,
            'article_id',
            'variant_id',
            'id',
            'id'
        )->with('image');
    }

    public function pivotImages(): HasMany
    {
        return $this->hasMany(ArticleImage::class);
    }

    public function global_score(): HasOne
    {
        return $this->hasOne(ArticleGlobalScore::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ArticleScore::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function favourites_users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserFavouriteArticle::class);
    }

    public function user_favourite_articles(): HasMany
    {
        return $this->hasMany(UserFavouriteArticle::class);
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Question::class,
            ArticleQuestion::class,
            'article_id',
            'id',
            'id',
            'question_id'
        );
    }

    public function article_questions(): HasMany
    {
        return $this->hasMany(ArticleQuestion::class);
    }

    public function carts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Cart::class,
            ArticleCart::class,
            'article_id',
            'id',
            'id',
            'cart_id'
        );
    }

    public function articleCarts(): HasMany
    {
        return $this->hasMany(ArticleCart::class);
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(
            Shop::class,
            'shop_articles',
            'article_id',
            'shop_id'
        )->distinct();
    }

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(VariantPrice::class, Variant::class);
    }

    public function eurPrices()
    {
        return $this->prices()->where('currency', 'EUR');
    }

    public function shopArticles(): HasMany
    {
        return $this->hasMany(ShopArticle::class);
    }

    public function doesHaveVariant(Variant $variant)
    {
        return $this->variants()->where('id', $variant->id)->exists();
    }
}
