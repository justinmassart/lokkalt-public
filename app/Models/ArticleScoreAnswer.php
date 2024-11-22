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
 * @property string $article_score_id
 * @property string $shop_id
 * @property string $user_id
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ArticleScoreAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereArticleScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleScoreAnswer whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleScoreAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
