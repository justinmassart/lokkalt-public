<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\RelationManagers;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'scores';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.scores');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user')
                    ->label(__('filament.customer'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        $firstname = $record->user->firstname;
                        $lastname = substr($record->user->lastname, 0, 1) . '.';

                        return "$firstname $lastname";
                    }),
                TextColumn::make('score')
                    ->label('filament.score')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
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
                TextColumn::make('comment')
                    ->label(__('filament.comment'))
                    ->toggleable()
                    ->wrap(),
                IconColumn::make('answered')
                    ->label(__('filament.answered?'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        $comment = $record->comment;
                        $answer = $record->answer->answer ?? null;
                        if ($comment && $answer) {
                            return true;
                        }
                        if (($comment && !$answer) || (!$comment && $answer)) {
                            return false;
                        }

                        return 'NO_APPLICATION';
                    })
                    ->icon(static function ($state) {
                        if ($state === true) {
                            return 'heroicon-s-check-circle';
                        }
                        if ($state === false) {
                            return 'heroicon-s-x-circle';
                        }

                        return 'heroicon-s-no-symbol';
                    })
                    ->color(static function ($state) {
                        if ($state === true) {
                            return 'success';
                        }
                        if ($state === false) {
                            return 'danger';
                        }

                        return 'grey';
                    })
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('filament.date'))
                    ->dateTime('j F Y - g:i')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->paginated([10, 20, 50, 'all'])
            ->defaultPaginationPageOption(10)
            ->filters([
                Filter::make('answer')
                    ->label(__('filament.answered?'))
                    ->form([
                        Select::make('is_answered')
                            ->label(__('filament.answered?'))
                            ->options([
                                true => __('filament.yes'),
                                false => __('filament.no'),
                                null => __('filament.not_needed'),
                            ]),
                    ])
                    ->query(static function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['is_answered'] === '1',
                                fn (Builder $query): Builder => $query->has('answer'),
                            )
                            ->when(
                                $data['is_answered'] === '0',
                                fn (Builder $query): Builder => $query->doesntHave('answer')
                                    ->whereNotNull('comment'),
                            )
                            ->when(
                                $data['is_answered'] === '',
                                fn (Builder $query): Builder => $query->whereNull('comment'),
                            );
                    }),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('answer')
                    ->label(__('filament.answer'))
                    ->modal()
                    ->modalHeading(static function (Model $record) {
                        $firstname = $record->user->firstname;
                        $lastname = substr($record->user->lastname, 0, 1) . '.';

                        return 'Anwser to : ' . "$firstname $lastname";
                    })
                    ->hidden(static function (Model $record) {
                        return !isset($record->comment);
                    })
                    ->form([
                        Section::make('About the client')
                            ->label(__('filament.about_the_customer'))
                            ->schema([
                                TextInput::make('client')
                                    ->label(__('filament.customer'))
                                    ->disabled()
                                    ->default(static function (Model $record) {
                                        $firstname = $record->user->firstname;
                                        $lastname = substr($record->user->lastname, 0, 1) . '.';

                                        return "$firstname $lastname";
                                    }),
                                TextInput::make('created_at')
                                    ->label(__('filament.date'))
                                    ->disabled()
                                    ->default(static function (Model $record) {
                                        return $record->created_at->format('j F Y - g:i');
                                    }),
                                Textarea::make('comment')
                                    ->label(__('filament.date'))
                                    ->disabled()
                                    ->default(static function (Model $record) {
                                        return $record->comment;
                                    })
                                    ->columnSpanFull()
                                    ->autosize(),
                            ])
                            ->columns(2),
                        Section::make('Your answer')
                            ->label(__('filament.your_answer'))
                            ->schema([
                                Textarea::make('answer')
                                    ->label(__('filament.answer'))
                                    ->maxLength(325)
                                    ->autosize()
                                    ->default(static function (Model $record) {
                                        return $record->answer->answer ?? '';
                                    }),
                            ]),
                    ])
                    ->action(static function (array $data, Model $record) {
                        $record->answer()->create([
                            'answer' => $data['answer'],
                            'user_id' => auth()->user()->id,
                            'shop_id' => session()->get('shop')->id,
                        ]);
                        Notification::make()
                            ->success()
                            ->title('Your answer was successfuly saved')
                            ->send();
                    }),
            ]);
    }
}
