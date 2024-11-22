<?php

use App\Filament\Dashboard\Resources\ArticleResource;
use App\Filament\Dashboard\Resources\ArticleResource\Pages\ViewArticle;
use App\Models\Franchise;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

describe('Filament - ViewArticle', function () {
    $shop = collect();
    $franchise = collect();

    beforeEach(function () use (&$shop, &$franchise) {
        $franchise = Franchise::first();
        $user = $franchise->owner;
        $shop = $franchise->shops()->first();
        $this->withSession([
            'franchise' => $shop->franchise,
            'shop' => $shop,
        ]);
        actingAs($user);
    });

    it('can visit the view page of one of his article', function () use (&$shop, &$franchise) {
        $article = $shop->articles()->first();

        $this->get(ArticleResource::getUrl('view', ['record' => $article]))
            ->assertSuccessful()
            ->assertSee($article->name);
    });

    it('can not visit the view page of another seller’s articles', function () use (&$shop, &$franchise) {
        $otherArticle = Franchise::whereNot('id', $franchise->id)->first()->shops()->first()->articles()->first();

        $this->get(ArticleResource::getUrl('view', ['record' => $otherArticle]))->assertStatus(403);
    });

    it('can see the filled fields with the correct data for the article', function () use (&$shop, &$franchise) {
        $article = $shop->articles()->first();

        $this->get(ArticleResource::getUrl('view', ['record' => $article]))
            ->assertSuccessful()
            ->assertSee($article->name);

        $variants = [];

        foreach ($article->variants as $variant) {
            $prices = [];
            foreach ($variant->prices as $price) {
                $prices['record-' . $price->id] = [
                    'price' => $price->price,
                    'currency' => $price->currency,
                ];
            }

            $variants['record-' . $variant->id] = [
                'is_visible' => $variant->is_visible,
                'name' => $variant->name,
                'slug' => $variant->slug,
                'description' => $variant->description,
                'details' => $variant->details,
                'prices' => $prices,
            ];
        }

        livewire(ViewArticle::class, ['record' => $article->getRouteKey()])
            ->assertSuccessful()
            ->assertFormSet([
                'name' => $article->name,
                'slug' => $article->slug,
                'category_id' => $article->category->id,
                'sub_category_id' => $article->sub_category->id,
                'description' => $article->description,
                'details' => $article->details,
                'variants' => $variants,
            ]);

        // TODO: Do test for images
    });
});
