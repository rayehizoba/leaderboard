<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = User::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['md' => 3,])
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),

                                        TextInput::make('email')
                                            ->email()
                                            ->required(),

                                        Select::make('roles')
                                            ->multiple()
                                            ->relationship(
                                                'roles',
                                                'name',
                                                modifyQueryUsing: function (Builder $query) {
                                                    if (!auth()->user()->hasRole(Utils::getSuperAdminName())) {
                                                        $query->where('name', '!=', Utils::getSuperAdminName());
                                                    }
                                                    return $query;
                                                },
                                            )
                                            ->preload()
                                            ->searchable()
                                            ->columnSpanFull(),

                                        TextInput::make('password')
                                            ->label('New Password')
                                            ->autocomplete('new-password')
                                            ->password()
                                            ->confirmed()
                                            ->revealable()
                                            ->required(fn(?User $record) => !isset($record))
                                            ->dehydrated(fn($state) => isset($state))
                                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                            ->reactive()
                                            ->live(),

                                        TextInput::make('password_confirmation')
                                            ->password()
                                            ->dehydrated(false)
                                            ->revealable()
                                            ->required(function (Forms\Get $get) {
                                                $password = $get('password');
                                                return isset($password);
                                            })
                                            ->visible(function (Forms\Get $get) {
                                                $password = $get('password');
                                                return isset($password);
                                            })
                                            ->reactive()
                                            ->live(),
                                    ])
                            ])
                            ->columnSpan(['md' => 2]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('Created at')
                                            ->content(function (?User $record) {
                                                return isset($record)
                                                    ? Carbon::parse($record->created_at)->diffForHumans()
                                                    : '-';
                                            }),

                                        Forms\Components\Placeholder::make('Last modified at')
                                            ->content(function (?User $record) {
                                                return isset($record)
                                                    ? Carbon::parse($record->updated_at)->diffForHumans()
                                                    : '-';
                                            }),
                                    ])
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->badge(),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                \Tapp\FilamentInvite\Tables\InviteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
