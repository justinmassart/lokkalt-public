<?php

use App\Filament\Dashboard\Resources\ArticleResource;
use App\Filament\Dashboard\Resources\ArticleResource\Pages\ListArticles;
use App\Models\Franchise;
use App\Models\User;
use Filament\Actions\ViewAction;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

describe('Filament - ListArticles', function () {
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

    it('can see the list of his articles', function () use (&$shop, &$franchise) {
        $articles = $shop->articles()->get();

        $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
        livewire(ListArticles::class)
            ->assertCanSeeTableRecords($articles)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('variants.name')
            ->assertCanRenderTableColumn('category.name')
            ->assertCanRenderTableColumn('sub_category.name')
            ->assertCanRenderTableColumn('created_at');
    });

    it('can not see another seller’s articles', function () use (&$shop, &$franchise) {
        $articles = $shop->articles()->get();
        $otherArticles = Franchise::whereNot('id', $franchise->id)->first()->shops()->first()->articles;

        $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
        livewire(ListArticles::class)
            ->assertCanSeeTableRecords($articles)
            ->assertCanNotSeeTableRecords($otherArticles);
    });

    it('can sort the list of his articles', function () use (&$shop, &$franchise) {
        $articles = $shop->articles()->get();

        $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
        livewire(ListArticles::class)
            ->assertCanSeeTableRecords($articles)
            ->sortTable('name')
            ->assertCanSeeTableRecords($articles->sortBy('name'), inOrder: true)
            ->sortTable('name', 'desc')
            ->assertCanSeeTableRecords($articles->sortByDesc('name'), inOrder: true)
            ->sortTable('category.name')
            ->assertCanSeeTableRecords($articles->sortBy('category.name'), inOrder: true)
            ->sortTable('category.name', 'desc')
            ->assertCanSeeTableRecords($articles->sortByDesc('category.name'), inOrder: true)
            ->sortTable('sub_category.name')
            ->assertCanSeeTableRecords($articles->sortBy('sub_category.name'), inOrder: true)
            ->sortTable('sub_category.name', 'desc')
            ->assertCanSeeTableRecords($articles->sortByDesc('sub_category.name'), inOrder: true)
            ->sortTable('created_at')
            ->assertCanSeeTableRecords($articles->sortBy('created_at'), inOrder: true)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords($articles->sortByDesc('created_at'), inOrder: true);
    });

    it('can search in the list of his articles', function () use (&$shop, &$franchise) {
        $articles = $shop->articles()->get();

        $article = $articles->first();
        $articleName = $article->name;
        $articleCategory = $article->category;
        $articleSubCategory = $article->sub_category;
        $articleCreatedAt = $article->created_at;

        $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
        livewire(ListArticles::class)
            ->assertCanSeeTableRecords($articles)
            ->searchTable($articleName)
            ->assertCanSeeTableRecords($articles->where('name', $articleName))
            ->assertCanNotSeeTableRecords($articles->where('name', '!=', $articleName))
            ->searchTable()
            ->searchTable($articleCategory)
            ->assertCanSeeTableRecords($articles->where('category.name', $articleCategory))
            ->assertCanNotSeeTableRecords($articles->where('category.name', '!=', $articleCategory))
            ->searchTable()
            ->searchTable($articleSubCategory)
            ->assertCanSeeTableRecords($articles->where('sub_category.name', $articleSubCategory))
            ->assertCanNotSeeTableRecords($articles->where('sub_category.name', '!=', $articleSubCategory))
            ->searchTable()
            ->searchTable($articleCreatedAt)
            ->assertCanSeeTableRecords($articles->where('created_at', $articleCreatedAt))
            ->assertCanNotSeeTableRecords($articles->where('created_at', '!=', $articleCreatedAt));
    });

    it('can see the view action on the table and call it', function () use (&$shop, &$franchise) {
        $articles = $shop->articles()->get();

        $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
        livewire(ListArticles::class)
            ->assertCanSeeTableRecords($articles)
            ->assertTableActionExists(ViewAction::class, null, $articles->first())
            ->callTableAction('view', $articles->first());
    });
});
