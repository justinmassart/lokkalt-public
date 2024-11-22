<?php

namespace App\Filament\Dashboard\Resources\StockResource\Pages;

use App\Filament\Dashboard\Resources\StockResource;
use App\Filament\Dashboard\Resources\StockResource\RelationManagers\OperationsRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditStock extends EditRecord
{
    protected static string $resource = StockResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('filament.stock_of') . ' ' . $this->record->shopArticle->article->name . ' - ' . $this->record->shopArticle->variant->name . ' #' . $this->record->shopArticle->variant->reference;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['quantity'] = (int) $data['quantity'];
        $data['limited_stock_below'] = (int) $data['limited_stock_below'];

        $stock = $record;
        $stockBefore = $record->quantity;

        if ($data['quantity'] >= $data['limited_stock_below']) {
            $data['status'] = 'in';
        } elseif ($data['quantity'] < $data['limited_stock_below'] && $data['quantity'] > 0) {
            $data['status'] = 'limited';
        } else {
            $data['status'] = 'out';
        }

        $recordData = $data;
        unset($recordData['comment']);

        $record->update($recordData);

        $operation = '';

        if ($stockBefore > $data['quantity']) {
            $operation = '-' . ($stockBefore - $data['quantity']);
        } elseif ($stockBefore < $data['quantity']) {
            $operation = '+' . ($data['quantity'] - $stockBefore);
        } else {
            $operation = null;
        }

        if ($stockBefore === $data['quantity']) {
            return $record;
        }

        $stock->operations()->create([
            'stock_before' => $stockBefore,
            'stock_after' => $data['quantity'],
            'operation' => $operation,
            'comment' => $data['comment'],
            'user_id' => auth()->user()->id,
        ]);

        $this->dispatch('refreshStockOperations')->to(OperationsRelationManager::class);

        $this->refreshFormData([
            'status',
        ]);

        return $record;
    }
}
