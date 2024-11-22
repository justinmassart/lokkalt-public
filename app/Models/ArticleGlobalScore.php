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
 * @property string|null $score
 * @property int|null $total_votes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $article_id
 * @property-read \App\Models\Article $article
 * @method static \Database\Factories\ArticleGlobalScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereTotalVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleGlobalScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleGlobalScore extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
