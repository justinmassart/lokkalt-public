<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * 
 *
 * @property string $id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\ArticleQuestionAnswer|null $articleAnswer
 * @property-read \App\Models\ArticleQuestion|null $article_questions
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUserId($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function article(): HasOneThrough
    {
        return $this->hasOneThrough(
            Article::class,
            ArticleQuestion::class,
            'question_id',
            'id',
            'id',
            'article_id'
        );
    }

    public function article_questions(): BelongsTo
    {
        return $this->belongsTo(ArticleQuestion::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_questions', 'question_id', 'shop_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articleAnswer(): HasOne
    {
        return $this->hasOne(ArticleQuestionAnswer::class);
    }
}
