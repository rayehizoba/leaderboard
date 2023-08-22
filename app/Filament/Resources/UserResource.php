<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['md' => 3,])
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),

                                        TextInput::make('email')
                                            ->email()
                                            ->required(),

                                        TextInput::make('password')
                                            ->password()
                                            ->confirmed()
                                            ->required(fn (?User $record) => !isset($record))
                                            ->dehydrated(fn ($state) => isset($state))
                                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                            ->reactive(),

                                        TextInput::make('password_confirmation')
                                            ->password()
                                            ->dehydrated(false)
                                            ->required(function (Closure $get) {
                                                $password = $get('password');
                                                return isset($password);
                                            })
                                    ])
                            ])
                            ->columnSpan(['md' => 2]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Card::make()
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
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
