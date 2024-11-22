<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\Pages;

use App\Filament\Dashboard\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ShopArticle;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    public array $shopIDs = [];

    public function getTitle(): string|Htmlable
    {
        return 'Edit ' . $this->data['name'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['shops']);

        $data['slug'] = $this->data['slug'];

        $variants = $this->record->variants;

        foreach ($variants as $variant) {
            $variant->slug = str()->slug($variant->name);
            $variant->save();
        }

        return $data;
    }

    protected function beforeSave()
    {
        $this->shopIDs = $this->data['shops'];
    }

    protected function afterSave(): void
    {
        $shopIDs = $this->shopIDs['id'];
        foreach ($shopIDs as $shopID) {
            $article = Article::with('variants')->whereId($this->record->id)->first();
            foreach ($article->variants as $variant) {
                $shopArticle = ShopArticle::firstOrCreate([
                    'shop_id' => $shopID,
                    'article_id' => $this->record->id,
                    'variant_id' => $variant->id,
                ]);

                $hasStock = $shopArticle->stock()->exists();

                if ($hasStock) continue;

                $shopArticle->stock()->firstOrCreate([
                    'quantity' => 0,
                    'status' => 'out',
                    'limited_stock_below' => 5,
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
