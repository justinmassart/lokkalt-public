<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Livewire\WithFileUploads;
use Spatie\Tags\HasTags;

/**
 * 
 *
 * @property string $id
 * @property string $reference
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property array|null $details
 * @property bool $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $article_id
 * @property-read \App\Models\Article $article
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantImage> $pivotImages
 * @property-read int|null $pivot_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantPrice> $prices
 * @property-read int|null $prices_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopArticle> $shopArticle
 * @property-read int|null $shop_article_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopArticle> $shopArticles
 * @property-read int|null $shop_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shop> $shops
 * @property-read int|null $shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockOperation> $stockOperations
 * @property-read int|null $stock_operations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantImage> $variant_images
 * @property-read int|null $variant_images_count
 * @method static \Database\Factories\VariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @mixin \Eloquent
 */
class Variant extends Model
{
    use HasFactory, HasTags, HasUuids, WithFileUploads;

    protected $guarded = [];

    protected $casts = [
        'is_visible' => 'boolean',
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

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(
            Shop::class,
            'shop_articles',
            'variant_id',
            'shop_id'
        )->distinct();
    }

    public function shopArticles(): HasMany
    {
        return $this->hasMany(ShopArticle::class);
    }

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(
            Image::class,
            VariantImage::class,
            'variant_id',
            'id',
            'id',
            'image_id'
        );
    }

    public function variant_images(): HasMany
    {
        return $this->hasMany(VariantImage::class);
    }

    public function pivotImages(): HasMany
    {
        return $this->hasMany(VariantImage::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(VariantPrice::class);
    }

    public function stocks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Stock::class,
            ShopArticle::class,
            'variant_id',
            'shop_article_id',
            'id',
            'id',
        );
    }

    public function stockOperations(): HasManyThrough
    {
        return $this->hasManyThrough(
            StockOperation::class,
            Stock::class,
            'variant_id',
            'stock_id',
            'id',
            'id'
        );
    }

    public function shopArticle(): HasMany
    {
        return $this->hasMany(ShopArticle::class, 'variant_id');
    }
}
