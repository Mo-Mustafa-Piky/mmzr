<?php

namespace App\Filament\Resources\Communities;

use App\Filament\Resources\Communities\Pages\ListCommunities;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CommunityResource extends Resource
{
    protected static ?string $model = \App\Models\Community::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Communities';

    public static function getPages(): array
    {
        return [
            'index' => ListCommunities::route('/'),
        ];
    }
}