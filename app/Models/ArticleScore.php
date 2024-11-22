<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 
 *
 * @property string $id
 * @property string $score
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $article_id
 * @property string $user_id
 * @property-read \App\Models\ArticleScoreAnswer|null $answer
 * @property-read \App\Models\Article $article
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLikingArticleScore> $likings
 * @property-read int|null $likings_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ArticleScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScore whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleScore extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likings(): HasMany
    {
        return $this->hasMany(UserLikingArticleScore::class);
    }

    public function likesCount()
    {
        return $this->likings()->whereLiking('like');
    }

    public function dislikesCount()
    {
        return $this->likings()->whereLiking('dislike');
    }

    public function answer(): HasOne
    {
        return $this->hasOne(ArticleScoreAnswer::class);
    }
}
