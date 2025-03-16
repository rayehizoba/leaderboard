<?php

namespace App\Filament\Resources;

use App\Filament\Exports\LeaderboardExporter;
use App\Filament\Resources\LeaderboardResource\Pages;
use App\Filament\Resources\LeaderboardResource\Pages\EditLeaderboard;
use App\Filament\Resources\LeaderboardResource\RelationManagers;
use App\Models\Leaderboard;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LeaderboardResource extends Resource
{
    protected static ?string $model = Leaderboard::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['md' => 3,])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                        $set('slug', Str::slug($state));
                                                    }),

                                                Forms\Components\TextInput::make('slug')
                                                    ->required(),

                                                Forms\Components\TextInput::make('heading')
                                                    ->placeholder(function (Forms\Get $get) {
                                                        return $get('name');
                                                    }),

                                                ColorPicker::make('brand_color')
                                                    ->rgb()
                                                    ->default('rgb(255, 255, 255)')
                                                    ->required(),

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
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('Created at')
                                            ->content(function (?Leaderboard $record) {
                                                return isset($record)
                                                    ? Carbon::parse($record->created_at)->diffForHumans()
                                                    : '-';
                                            }),

                                        Forms\Components\Placeholder::make('Last modified at')
                                            ->content(function (?Leaderboard $record) {
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
                    ->label('Leaderboard')
                    ->weight('bold')
                    ->size(TextColumn\TextColumnSize::Large)
                    ->sortable()
                    ->searchable()
                    ->limit(35),
                ImageColumn::make('logo_file_path')
                    ->label('Logo')
                    ->toggleable(),
                TextColumn::make('leaders.name')
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->url('javascript:void(0)')
                    ->searchable()
                    ->toggleable(),
                ColorColumn::make('brand_color')
                    ->label('Color')
                    ->copyable()
                    ->copyMessage('Color code copied')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordUrl(
                fn(Leaderboard $record): string => EditLeaderboard::getUrl(['record' => $record]),
            )
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('view')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn(Leaderboard $record): string => route('clients.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
//                    ExportAction::make()
//                        ->exporter(LeaderboardExporter::class)
                ]),
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
            'index' => Pages\ListLeaderboards::route('/'),
            'create' => Pages\CreateLeaderboard::route('/create'),
            'edit' => Pages\EditLeaderboard::route('/{record}/edit'),
        ];
    }
}
