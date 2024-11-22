<?php

namespace App\Filament\Dashboard\Resources\ShopResource\RelationManagers;

use App\Models\Image;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.shop_images');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('images.url')
                    ->label(__('filament.shop_images'))
                    ->columnSpanFull()
                    ->panelLayout('grid')
                    ->uploadingMessage(__('filament.uploading_images'))
                    ->maxSize(10240)
                    ->multiple(static function (RelationManager $livewire) {
                        return 4 - $livewire->getOwnerRecord()->images()->count() === 1 ? false : true;
                    })
                    ->minFiles(1)
                    ->maxFiles(static function (RelationManager $livewire) {
                        return 4 - $livewire->getOwnerRecord()->images()->count();
                    })
                    ->reorderable()
                    ->appendFiles()
                    ->downloadable()
                    ->storeFiles(false)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                    ])
                    ->imageResizeMode('cover'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('url')
            ->columns([
                Stack::make([
                    ToggleColumn::make('is_main_image')
                        ->label('Main Image ?')
                        ->onIcon('heroicon-s-heart')
                        ->onColor('primary')
                        ->offIcon('heroicon-o-heart')
                        ->offColor('gray')
                        ->afterStateUpdated(function (Model $record) {
                            $oldArticleImageID = $this->ownerRecord
                                ->images()
                                ->where('image_id', '!=', $record->id)
                                ->where('is_main_image', true)
                                ->first();

                            if ($oldArticleImageID) {
                                $oldArticleImageID->update([
                                    'is_main_image' => false,
                                ]);
                            }

                            $record->update([
                                'is_main_image' => true,
                            ]);
                        }),
                    ImageColumn::make('images.url')
                        ->disk('s3')
                        ->defaultImageUrl(function (Model $record) {
                            return Storage::disk('s3')->temporaryUrl('web/medium/' . $record->url, now()->addMinutes(30));
                        })
                        ->height(200)
                        ->width('')
                        ->extraImgAttributes(['loading' => 'lazy'])
                        ->alignCenter(),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading(static function (RelationManager $livewire) {
                        return __('filament.add_common_images_for') . '' . $livewire->getOwnerRecord()->name;
                    })
                    ->label(__('filament.add_images'))
                    ->modalSubmitActionLabel(__('filament.upload'))
                    ->createAnother(false)
                    ->hidden(static function (RelationManager $livewire) {
                        return $livewire->getOwnerRecord()->images()->count() === 4 ? true : false;
                    })
                    ->action(static function (array $data, RelationManager $livewire) {
                        $image = new Image();
                        $shop = $livewire->getOwnerRecord();
                        $analyze = $image->uploadImages($data['images'], $shop);
                        if (count($analyze) >= 1) {
                            $analyze = array_unique($analyze);
                            Notification::make()
                                ->title('Something went wrong...')
                                ->body(static function () use ($analyze) {
                                    $message = 'One or multiple images did not respect the images policies of Lokkalt and were automatically removed.' . '<br><br>' . 'Here are the possible subjects contained in the previous forbidden images :' . '<br><br>';
                                    $message .= '<b>' . implode(', ', $analyze) . '</b>';
                                    $message .= '<br><br>' . 'You can close this notification by pressing the icon on the top right.';

                                    return $message;
                                })
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->action(static function (Model $record) {
                        $record->deleteShopImage($record);

                        Notification::make()
                            ->title('Image deleted successfully')
                            ->icon('heroicon-c-check-circle')
                            ->iconColor('primary')
                            ->send();
                    }),
            ]);
    }
}
