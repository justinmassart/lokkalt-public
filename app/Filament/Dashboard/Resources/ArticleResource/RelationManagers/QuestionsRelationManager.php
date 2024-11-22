<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\RelationManagers;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.questions');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('content')
                    ->label(__('filament.question')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user')
                    ->label(__('filament.user'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        $firstname = $record->user->firstname;
                        $lastname = substr($record->user->lastname, 0, 1) . '.';

                        return "$firstname $lastname";
                    }),
                TextColumn::make('content')
                    ->label(__('filament.question'))
                    ->toggleable()
                    ->wrap(),
                IconColumn::make('answered')
                    ->label(__('filament.answered?'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        $question = $record->content;
                        $answer = $record->articleAnswer->answer ?? null;
                        if ($question && $answer) {
                            return true;
                        }
                        if (($question && !$answer) || (!$question && $answer)) {
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
                            ]),
                    ])
                    ->query(static function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['is_answered'] === '1',
                                fn (Builder $query): Builder => $query->has('articleAnswer'),
                            )
                            ->when(
                                $data['is_answered'] === '0',
                                fn (Builder $query): Builder => $query->doesntHave('articleAnswer'),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['is_answered'] === null) {
                            return null;
                        }

                        return 'Is Answered ? : ' . ($data['is_answered'] === '1' ? __('filament.yes') : __('filament.no'));
                    }),
            ])
            ->actions([
                Action::make('answer')
                    ->label(__('buttons.answer'))
                    ->modal()
                    ->modalHeading(static function (Model $record) {
                        $firstname = $record->user->firstname;
                        $lastname = substr($record->user->lastname, 0, 1) . '.';

                        return 'Anwser to : ' . "$firstname $lastname";
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
                                    ->label(__('filament.comment'))
                                    ->disabled()
                                    ->default(static function (Model $record) {
                                        return $record->content;
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
                                        return $record->articleAnswer->answer ?? '';
                                    }),
                            ]),
                    ])
                    ->action(static function (array $data, Model $record) {
                        $record->articleAnswer()->create([
                            'answer' => $data['answer'],
                            'user_id' => auth()->user()->id,
                            'shop_id' => session()->get('shop')->id,
                        ]);
                        Notification::make()
                            ->title('Your answer was successfuly saved')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
