<?php

namespace App\Filament\Resources\SubCommunities;

use App\Filament\Resources\SubCommunities\Pages\ListSubCommunities;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SubCommunityResource extends Resource
{
    protected static ?string $model = \App\Models\SubCommunity::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Sub-Communities';

    public static function getPages(): array
    {
        return [
            'index' => ListSubCommunities::route('/'),
        ];
    }
}