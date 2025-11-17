<?php

namespace App\Filament\Resources\Agents;

use App\Filament\Resources\Agents\Pages\ListAgents;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AgentResource extends Resource
{
    protected static ?string $model = \App\Models\Agent::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Agents';

    public static function getPages(): array
    {
        return [
            'index' => ListAgents::route('/'),
        ];
    }
}