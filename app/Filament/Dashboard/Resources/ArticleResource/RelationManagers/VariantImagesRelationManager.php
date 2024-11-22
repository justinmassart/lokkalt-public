<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\RelationManagers;

use App\Models\Image;
use App\Models\Variant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VariantImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'variantImages';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.variants_images');
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('variants.name')
                    ->label(__('filament.availale_variants'))
                    ->hint('Only the variants without 4 images will be displayed in the list.')
                    ->options(static function (RelationManager $livewire) {
                        $variants = $livewire->ownerRecord->variants;
                        $variantsOptions = [];
                        foreach ($variants as $variant) {
                            if ($variant->images()->count() < 4) {
                                $variantsOptions[$variant->id] = $variant->name;
                            }
                        }

                        return $variantsOptions;
                    })
                    ->searchable()
                    ->required()
                    ->live(onBlur: true)
                    ->preload()
                    ->columnSpanFull(),
                FileUpload::make('variantImages.url')
                    ->label(__('filament.common_images'))
                    ->hint(function (Get $get, RelationManager $livewire) {
                        $variant_id = $get('variants.name');

                        if (!$variant_id) return 0;

                        $variant = $livewire->ownerRecord->variants()->where('id', $variant_id)->first();
                        $imagesCount = $variant->images()->count();
                        $maxImages = 4 - $imagesCount;

                        return "The first image will be used as the main image. You can reorder the images by click&drag them. Max $maxImages images.";
                    })
                    ->columnSpanFull()
                    ->panelLayout('grid')
                    ->uploadingMessage(__('filament.uploading_images'))
                    ->maxSize(10240)
                    ->multiple(static function (RelationManager $livewire, Get $get) {
                        $variant_id = $get('variants.name');

                        if (!$variant_id) return 0;
                        $variant = $livewire->ownerRecord->variants()->where('id', $variant_id)->first();

                        return 4 - $variant->images()->count() === 1 ? false : true;
                    })
                    ->maxFiles(static function (RelationManager $livewire, Get $get) {
                        $variant_id = $get('variants.name');

                        if (!$variant_id) return 0;
                        $variant = $livewire->ownerRecord->variants()->where('id', $variant_id)->first();

                        return 4 - $variant->images()->count();
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
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->hidden(function (Get $get) {
                        $hasVariant = $get('variants.name');

                        return $hasVariant !== null ? false : true;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('url')
            ->description(__('filament.image_toggle'))
            ->columns([
                Stack::make([
                    ToggleColumn::make('image.is_main_image')
                        ->label('Main Image ?')
                        ->onIcon('heroicon-s-heart')
                        ->onColor('primary')
                        ->offIcon('heroicon-o-heart')
                        ->offColor('gray')
                        ->afterStateUpdated(function (Model $record) {
                            $oldVariantImageID = $record->variant
                                ->images()
                                ->where('image_id', '!=', $record->image_id)
                                ->where('is_main_image', true)
                                ->first();

                            if ($oldVariantImageID) {
                                $oldVariantImageID->update([
                                    'is_main_image' => false,
                                ]);
                            }

                            $record->image()->update([
                                'is_main_image' => true,
                            ]);
                        }),
                    ImageColumn::make('url')
                        ->disk('s3')
                        ->defaultImageUrl(function (Model $record) {
                            return Storage::disk('s3')->temporaryUrl('web/small/' . $record->image->url, now()->addMinutes(30));
                        })
                        ->width(200)
                        ->height('')
                        ->extraImgAttributes(['loading' => 'lazy'])
                        ->alignCenter(),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 4,
            ])
            ->groups([
                Group::make('variant.name')
                    ->label('Variant Name')
                    ->collapsible(),
            ])
            ->defaultGroup('variant.name')
            ->groupingSettingsHidden()
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading(__('filament.add_images_to_variant'))
                    ->label(__('filament.add_images'))
                    ->modalSubmitActionLabel(__('filament.upload'))
                    ->createAnother(false)
                    ->hidden(static function (RelationManager $livewire) {
                        return $livewire->getOwnerRecord()->variantImages()->count() >= $livewire->ownerRecord->variants()->count() * 4 ? true : false;
                    })
                    ->action(static function (array $data, RelationManager $livewire) {
                        $image = new Image();
                        $variant = Variant::whereId($data['variants']['name'])->first();
                        $analyze = $image->uploadImages($data['variantImages'], $variant);
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
                        $record->image->deleteVariantImage();
                        Notification::make()
                            ->title('Image deleted successfully')
                            ->icon('heroicon-s-trash')
                            ->iconColor('success')
                            ->send();
                    }),
            ]);
    }
}
