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
 * @property string $liking
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property string $article_score_id
 * @property-read \App\Models\ArticleScore $articleScore
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserLikingArticleScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereArticleScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereLiking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLikingArticleScore whereUserId($value)
 * @mixin \Eloquent
 */
class UserLikingArticleScore extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articleScore(): BelongsTo
    {
        return $this->belongsTo(ArticleScore::class);
    }
}
