<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_verification_tokens', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_password_reset_tokens', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_account_deletion_tokens', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_email_update_tokens', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_phone_update_tokens', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->foreignUuid('category_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignUuid('category_id');
            $table->foreignUuid('sub_category_id');
        });

        Schema::table('shop_articles', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('variant_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_images', function (Blueprint $table) {
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('image_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
            $table->unique(['slug', 'article_id']);
        });

        Schema::table('variant_prices', function (Blueprint $table) {
            $table->foreignUuid('variant_id')->constrained()->cascadeOnDelete();
            $table->unique(['currency', 'variant_id']);
        });

        Schema::table('variant_images', function (Blueprint $table) {
            $table->foreignUuid('variant_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('image_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_global_scores', function (Blueprint $table) {
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_scores', function (Blueprint $table) {
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignUuid('shop_article_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('stock_operations', function (Blueprint $table) {
            $table->foreignUuid('stock_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id');
        });

        Schema::table('user_favourite_articles', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_favourite_shops', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_liking_article_scores', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('article_score_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_liking_shop_scores', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('shop_score_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_questions', function (Blueprint $table) {
            $table->foreignUuid('article_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_carts', function (Blueprint $table) {
            $table->foreignUuid('shop_article_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('cart_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_preferences', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('pack_prices', function (Blueprint $table) {
            $table->foreignUuid('pack_id')->constrained();
        });

        Schema::table('pack_features', function (Blueprint $table) {
            $table->foreignUuid('pack_id')->constrained();
        });

        Schema::table('shop_packs', function (Blueprint $table) {
            $table->foreignUuid('pack_id');
            $table->foreignUuid('shop_id');
        });

        Schema::table('shop_images', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('image_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('shop_scores', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('shop_owners', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('shop_employees', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('shop_global_scores', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_score_answers', function (Blueprint $table) {
            $table->foreignUuid('article_score_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('article_question_answers', function (Blueprint $table) {
            $table->foreignUuid('question_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignUuid('user_id');
            $table->foreignUuid('shop_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignUuid('order_id');
            $table->foreignUuid('shop_article_id');
        });

        Schema::table('shop_registration_tokens', function (Blueprint $table) {
            $table->foreignUuid('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->foreignUuid('franchise_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('franchise_owners', function (Blueprint $table) {
            $table->foreignUuid('franchise_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('franchise_registration_tokens', function (Blueprint $table) {
            $table->foreignUuid('franchise_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('franchise_packs', function (Blueprint $table) {
            $table->foreignUuid('franchise_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('pack_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('franchise_subscriptions', function (Blueprint $table) {
            $table->foreignUuid('franchise_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_verification_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_password_reset_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_account_deletion_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_email_update_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_phone_update_tokens', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['sub_category_id']);
        });

        Schema::table('article_images', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['image_id']);
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
        });

        Schema::table('variant_prices', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
        });

        Schema::table('variant_images', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['image_id']);
        });

        Schema::table('article_global_scores', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
        });

        Schema::table('article_scores', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['shop_article_id']);
        });

        Schema::table('user_favourite_articles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['article_id']);
        });

        Schema::table('user_favourite_shops', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['shop_id']);
        });

        Schema::table('user_liking_article_scores', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_liking_shop_scores', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('article_questions', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['question_id']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('article_carts', function (Blueprint $table) {
            $table->dropForeign(['shop_article_id']);
            $table->dropForeign(['cart_id']);
        });

        Schema::table('user_preferences', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('feature_packs', function (Blueprint $table) {
            $table->dropForeign(['feature_id']);
            $table->dropForeign(['pack_id']);
        });

        Schema::table('pack_prices', function (Blueprint $table) {
            $table->dropForeign(['pack_id']);
            $table->dropForeign(['price_id']);
        });

        Schema::table('shop_images', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['image_id']);
        });

        Schema::table('shop_scores', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        Schema::table('shop_owners', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('shop_employees', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('shop_global_scores', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        Schema::table('article_score_answers', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('article_question_answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['shop_article_id']);
        });
    }
};
