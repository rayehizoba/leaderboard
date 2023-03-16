<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['md' => 3,])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Closure $set, $state) {
                                                        $set('slug', Str::slug($state));
                                                    }),

                                                Forms\Components\TextInput::make('slug')->required(),

                                                FileUpload::make('logo_file_path')
                                                    ->image()
                                                    ->label('Logo')
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ])
                                            ->columnSpan(['md' => 2]),
                                    ]),
                            ])
                            ->columnSpan(['md' => 2]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Card::make()
                                    ->schema([
                                        ColorPicker::make('brand_color')
                                            ->rgb()
                                            ->required(),
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('Created at')
                                            ->content(function (?Client $record) {
                                                return isset($record)
                                                    ? Carbon::parse($record->created_at)->diffForHumans()
                                                    : '-';
                                            }),

                                        Forms\Components\Placeholder::make('Last modified at')
                                            ->content(function (?Client $record) {
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
                ImageColumn::make('logo_file_path')
                    ->label('Logo'),
                TextColumn::make('name')
                    ->weight('bold')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => route('clients.show', $state))
                    ->url(fn(Client $record): string => route('clients.show', $record->slug))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-external-link')
                    ->iconPosition('after'),
                TextColumn::make('updated_at')
                    ->since(),
                ColorColumn::make('brand_color')
                    ->label('Color')
                    ->copyable()
                    ->copyMessage('Color code copied'),
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
            RelationManagers\LeadersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
