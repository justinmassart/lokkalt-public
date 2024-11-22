<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ArticleResource\Pages;
use App\Filament\Dashboard\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Models\Shop;
use App\Models\ShopArticle;
use App\Models\SubCategory;
use App\Models\Variant;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Livewire\Component as LivewireComponent;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $activeNavigationIcon = 'heroicon-s-tag';

    // TODO: add more "price per" to prices

    public static function form(Form $form): Form
    {
        $shops = auth()->user()->shops;

        return $form
            ->schema([
                Section::make(__('filament.quick_actions_section'))
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('filament.is_active?'))
                            ->onIcon('heroicon-c-check')
                            ->onColor('success')
                            ->offIcon('heroicon-c-x-mark')
                            ->offColor('danger')
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Section::make(__('filament.about_the_article_section'))
                    ->description(static function (?Model $record) {
                        return !$record ? __('filament.create_article_explanation') : null;
                    })
                    ->columns(2)
                    ->schema([
                        Select::make('shops.id')
                            ->relationship('shops')
                            ->label(__('filament.shops'))
                            ->native(false)
                            ->multiple()
                            ->options(function () use ($shops) {
                                $items = $shops->select('id', 'name', 'address');

                                $array = $items->mapWithKeys(function ($item) {
                                    return [$item['id'] => $item['name'] . ' - ' . $item['address']];
                                });

                                return $array;
                            })
                            ->exists(table: Shop::class, column: 'id')
                            ->searchable()
                            ->required()
                            ->live(onBlur: true)
                            ->saveRelationshipsUsing(function (Model $record, $state, Get $get) use ($shops) {
                                $SelectedShopIDs = $state;
                                $shopsIDs = $shops->pluck('id');
                                $variants = $get('variants');

                                $variantsIDs = array_map(function ($variant) {
                                    return $variant['id'] ?? null;
                                }, $variants);

                                foreach ($shopsIDs as $shopID) {
                                    if (!collect($SelectedShopIDs)->contains($shopID)) {
                                        ShopArticle::where('shop_id', $shopID)->where('article_id', $record->id)->delete();

                                        continue;
                                    }

                                    foreach ($variantsIDs as $recordID => $variantID) {
                                        if (!$variantID) {
                                            continue;
                                        }
                                        ShopArticle::firstOrCreate([
                                            'shop_id' => $shopID,
                                            'article_id' => $record->id,
                                            'variant_id' => $variantID,
                                        ]);
                                    }
                                }
                            }),
                        TextInput::make('name')
                            ->label(__('filament.name'))
                            ->required()
                            ->live(onBlur: true)
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    $check = session()->get('shop')
                                        ->articles()
                                        ->where('name', $value)
                                        ->exists();

                                    if ($check) {
                                        $fail(__('validation.unique', ['attribute' => $attribute]));
                                    }
                                },
                            ])
                            ->afterStateUpdated(function ($state, Component $component, HasForms $livewire) {
                                $check = session()->get('shop')
                                    ->articles()
                                    ->where('name', $state)
                                    ->exists();

                                if ($check) {
                                    $livewire->validate([
                                        $component->getStatePath() => 'unique:articles,name',
                                    ]);
                                } else {
                                    $livewire->validate([
                                        $component->getStatePath() => 'required',
                                    ]);
                                }
                            })
                            ->maxLength(255)
                            ->placeholder('Cheese wheel, beef steack, jaguar dress, alcohol glasses, ...'),
                        Select::make('category_id')
                            ->native(false)
                            ->label(__('filament.category'))
                            ->options(function () {
                                $categories = Category::all()->pluck('name', 'id');
                                $options = [];

                                foreach ($categories as $id => $name) {
                                    $options[] = [$id => __('categories.' . $name)];
                                }

                                return $options;
                            })
                            ->searchable()
                            ->required()
                            ->live(onBlur: true)
                            ->exists(table: Category::class, column: 'id')
                            ->afterStateUpdated(static function (Set $set) {
                                $set('sub_category_id', null);
                            }),
                        Select::make('sub_category_id')
                            ->native(false)
                            ->label(__('filament.sub_category'))
                            ->live(onBlur: true)
                            ->searchable()
                            ->required()
                            ->exists(table: SubCategory::class, column: 'id')
                            ->options(static function (Get $get) {
                                $subCategories = SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id');
                                $options = [];

                                foreach ($subCategories as $id => $name) {
                                    $options[] = [$id => __('categories.' . $name)];
                                }

                                return $options;
                            }),
                        Textarea::make('description')
                            ->label(__('filament.common_description'))
                            ->hint(__('filament.max_325_characters'))
                            ->minLength(5)
                            ->maxLength(325)
                            ->rows(3)
                            ->autosize()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make(__('filament.common_details'))
                    ->schema([
                        KeyValue::make('details')
                            ->label(__('filament.details'))
                            ->hint('You can register here the details that are shared with all variants of this article.')
                            ->keyLabel(__('filament.features'))
                            ->keyPlaceholder('Weight, Color, Size, Liters, ...')
                            ->valueLabel(__('filament.value'))
                            ->valuePlaceholder('250g, blue, M, 3.5L'),
                    ])
                    ->columnSpanFull(),
                Section::make(__('filament.common_images'))
                    ->schema([
                        FileUpload::make('images.url')
                            ->label(__('filament.images'))
                            ->columnSpanFull()
                            ->panelLayout('grid')
                            ->uploadingMessage('Uploading images...')
                            ->maxSize(10240)
                            ->multiple(static function ($state) {
                                return 4 - count($state) === 1 ? false : true;
                            })
                            ->minFiles(1)
                            ->maxFiles(4)
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
                            ->imageCropAspectRatio('16:9'),
                    ])
                    ->hidden(fn (string $operation): bool => $operation !== 'create'),
                Section::make(__('filament.variants'))
                    ->collapsible()
                    ->schema([
                        Repeater::make('variants')
                            ->hiddenLabel()
                            ->relationship('variants')
                            ->addActionLabel(__('filament.add_variant'))
                            ->minItems(1)
                            ->maxItems(10)
                            ->reorderable(false)
                            ->columns(2)
                            ->grid([
                                'md' => 1,
                                'xl' => 2,
                            ])
                            ->columnSpanFull()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                                $data['reference'] = str()->random(12);
                                while (Variant::whereReference($data['reference'])->exists()) {
                                    $data['reference'] = str()->random(12);
                                }

                                $shopSlug = auth()->user()->shops()->first()->slug;
                                $data['slug'] = str()->slug($data['name']);
                                $manager = new ImageManager(new GdDriver());

                                $imageModel = new Image();

                                $images = $imageModel->validateImages($data['variantImages']);

                                $data['variantImages'] = $images;

                                foreach ($data['variantImages']['url'] as $index => $url) {
                                    $manager->read(storage_path('app/livewire-tmp/' . $url->getFileName()));
                                    Storage::disk('local')->putFileAs('livewire-tmp/' . $shopSlug . '-' . $data['slug'], new File(storage_path('app/livewire-tmp/' . $url->getFileName())), $url->getFileName());
                                    unlink(storage_path('app/livewire-tmp/' . $url->getFileName()));
                                }
                                unset($data['variantImages']);

                                return $data;
                            })
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label(__('filament.visible?'))
                                    ->onIcon('heroicon-c-check')
                                    ->onColor('success')
                                    ->offIcon('heroicon-c-x-mark')
                                    ->offColor('danger')
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('name')
                                    ->label(__('filament.name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->different('../../name')
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $variants = $get('../../variants');
                                            $nameCounts = array_count_values(array_column($variants, 'name'));
                                            if (isset($nameCounts[$value]) && $nameCounts[$value] > 1) {
                                                $fail(__('validation.variant_name_not_unique'));
                                            }
                                        },
                                    ])
                                    ->afterStateUpdated(function ($state, Component $component, LivewireComponent $livewire) {
                                        $variants = $component->getParentRepeater()->getContainer()->getLivewire()->data['variants'];

                                        $id = $component->getId();
                                        $pattern = '/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i';
                                        preg_match($pattern, $id, $matches);
                                        $uuid = $matches[0] ?? null;

                                        unset($variants[$uuid]);

                                        foreach ($variants as $variant) {
                                            $check = $variant['name'] === $state;
                                            if ($check) {
                                                $livewire->addError($component->getStatePath(), __('validation.variant_name_not_unique'));
                                            } else {
                                                $livewire->validate([
                                                    $component->getStatePath() => 'required',
                                                ]);
                                            }
                                        }
                                    }),
                                Textarea::make('description')
                                    ->label(__('filament.description'))
                                    ->hint('Max 325 characters')
                                    ->minLength(5)
                                    ->maxLength(325)
                                    ->rows(3)
                                    ->autosize()
                                    ->columnSpanFull(),
                                FileUpload::make('variantImages.url')
                                    ->label(__('filament.images'))
                                    ->columnSpanFull()
                                    ->panelLayout('grid')
                                    ->uploadingMessage('Uploading images...')
                                    ->maxSize(10240)
                                    ->multiple()
                                    ->maxFiles(4)
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
                                    ->hidden(fn (string $operation): bool => $operation !== 'create'),
                                Repeater::make('prices')
                                    ->relationship('prices')
                                    ->required()
                                    ->label(__('filament.prices'))
                                    ->addActionLabel(__('filament.add_price'))
                                    ->reorderable(false)
                                    ->addable(static function ($state) {
                                        return count($state) === 3 ? false : true;
                                    })
                                    ->grid([
                                        'sm' => 1,
                                        'md' => 2,
                                        '2xl' => 3,
                                    ])
                                    ->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => $state['currency'] ?? null)
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        if (isset($data['price'])) {
                                            $data['price'] = str_replace(',', '.', $data['price']);
                                        }

                                        return $data;
                                    })
                                    ->schema([
                                        TextInput::make('price')
                                            ->label(__('filament.price'))
                                            ->required()
                                            ->inputMode('decimal')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(10000)
                                            ->step(0.01)
                                            ->columnSpanFull()
                                            ->live(onBlur: true),
                                        Select::make('per')
                                            ->label(__('filament.price_per'))
                                            ->native(false)
                                            ->required()
                                            ->columnSpanFull()
                                            ->searchable()
                                            ->options([
                                                'unit' => __('units.Unit'),
                                                'kg' => __('units.Kilogram'),
                                                'g' => __('units.Gram'),
                                                'L' => __('units.Liter'),
                                            ])
                                        /* ->createOptionForm([
                                                TextInput::make('Price per')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(static function (array $data, Set $set) {
                                                $set('per', [$data['Price per'] => $data['Price per']]);
                                            }) */,
                                        Select::make('currency')
                                            ->label(__('filament.currency'))
                                            ->native(false)
                                            ->required()
                                            ->columnSpanFull()
                                            ->options([
                                                'EUR' => 'EUR',
                                                'GBP' => 'GBP',
                                                'USD' => 'USD',
                                            ])
                                            ->in([
                                                'EUR' => 'EUR',
                                                'GBP' => 'GBP',
                                                'USD' => 'USD',
                                            ])
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                    ]),
                                KeyValue::make('details')
                                    ->label(__('filament.details'))
                                    ->hint('You can register here the details about this variant.')
                                    ->keyLabel(__('filament.features'))
                                    ->keyPlaceholder('Weight, Color, Size, Liters, ...')
                                    ->valueLabel(__('filament.value'))
                                    ->valuePlaceholder('250g, blue, M, 3.5L')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $shop = session()->get('shop');

        return $table
            ->query(
                /* ShopArticle::whereShopId($shop->id)
                    ->with([
                        'article',
                        'variant',
                        'shop',
                    ])
                    ->withAvg('scores', 'score') */

                Article::whereHas('shopArticles', function ($query) use ($shop) {
                    $query->where('shop_id', $shop->id);
                })
                    ->with([
                        'variants',
                        'category',
                        'sub_category',
                    ])
                    ->withAvg('scores', 'score')
            )
            ->description(__('filament.articles_table_description'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('scores_avg_score')
                    ->label(__('filament.rating') . ' /5')
                    ->toggleable()
                    ->sortable()
                    ->numeric(decimalPlaces: 2)
                    ->weight(FontWeight::Bold)
                    ->color(static function ($state) {
                        if ($state >= 3.75) {
                            return 'success';
                        }

                        if ($state < 3.75 && $state >= 2.5) {
                            return 'warning';
                        }

                        return 'danger';
                    }),
                TextColumn::make('is_active')
                    ->label(__('filament.is_active?'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        return $record->is_active == true ? 'Active' : 'Inactive';
                    })
                    ->color(
                        static function (Model $record) {
                            return $record->is_active == true ? 'success' : 'danger';
                        }
                    ),
                TextColumn::make('variants.name')
                    ->label(__('filament.variant'))
                    ->toggleable()
                    ->searchable()
                    ->listWithLineBreaks()
                    /*                     ->color(static function (Model $record) {
                        if ($record->variants->count() === 0) {
                            return $record->variants->count() === 0 ? 'danger' : null;
                        }
                    })
                    ->weight(static function (Model $record) {
                        return $record->variants->count() === 0 ? 'semibold' : null;
                    }) */
                    ->default('No variants'),
                TextColumn::make('category.name')
                    ->label(__('filament.category'))
                    ->toggleable()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return __('categories.' . $state);
                    }),
                TextColumn::make('sub_category.name')
                    ->label(__('filament.sub_category'))
                    ->toggleable()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return __('categories.' . $state);
                    }),
                TextColumn::make('created_at')
                    ->label(__('filament.added_at'))
                    ->dateTime('d M y')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('filament.category'))
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_category_id')
                    ->label(__('filament.sub_category'))
                    ->relationship('sub_category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                /* ->url(fn (ShopArticle $record): string => ArticleResource::getUrl('view', [$record->article->slug])) */,
                Tables\Actions\EditAction::make()
                /* ->url(fn (ShopArticle $record): string => ArticleResource::getUrl('edit', [$record->article->slug])) */,
            ])
            ->paginated([10, 20, 30]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
            RelationManagers\VariantImagesRelationManager::class,
            RelationManagers\ScoresRelationManager::class,
            RelationManagers\QuestionsRelationManager::class,
            // RelationManagers\VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.article');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.articles');
    }
}
