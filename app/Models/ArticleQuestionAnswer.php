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
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $question_id
 * @property string $shop_id
 * @property string $user_id
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleQuestionAnswer whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleQuestionAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
