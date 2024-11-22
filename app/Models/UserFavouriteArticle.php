<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * 
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property string $article_id
 * @property-read \App\Models\Article $article
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleScore> $scores
 * @property-read int|null $scores_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserFavouriteArticleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavouriteArticle whereUserId($value)
 * @mixin \Eloquent
 */
class UserFavouriteArticle extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function scores(): HasManyThrough
    {
        return $this->hasManyThrough(
            ArticleScore::class,
            Article::class,
            'id',
            'article_id',
            'article_id',
            'id'
        );
    }
}
