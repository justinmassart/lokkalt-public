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
 * @property string $article_id
 * @property string $image_id
 * @property-read \App\Models\Article $article
 * @property-read \App\Models\Image $image
 * @method static \Database\Factories\ArticleImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleImage extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
