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
 * @property string $question_id
 * @property-read \App\Models\Article $article
 * @property-read \App\Models\Question $question
 * @method static \Database\Factories\ArticleQuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
