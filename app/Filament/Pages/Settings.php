<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{
    use HasPageShield;

    protected static bool $shouldRegisterNavigation = false;

    public function schema(): array|Closure
    {
        return [
            Grid::make(['md' => 3,])
                ->schema([
                    Section::make([
                        TextInput::make('general.brand_name')
                            ->placeholder(config('app.name')),
                        FileUpload::make('general.brand_logo')
                            ->image()
                            ->imageEditor()
                    ])
                        ->columnSpan(['md' => 2]),
                ]),
        ];
    }
}
