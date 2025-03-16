<?php

namespace App\Filament\Resources\LeaderboardResource\Pages;

use App\Filament\Resources\LeaderboardResource;
use App\Models\Leaderboard;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaderboard extends EditRecord
{
    protected static string $resource = LeaderboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn(Leaderboard $record): string => route('clients.show', $record->slug))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
