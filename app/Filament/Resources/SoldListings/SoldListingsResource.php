<?php

namespace App\Filament\Resources\SoldListings;

use App\Filament\Resources\SoldListings\Pages\ListSoldListings;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class SoldListingsResource extends Resource
{
    protected static ?string $model = \App\Models\SoldListing::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Sold Listings';

    public static function getPages(): array
    {
        return [
            'index' => ListSoldListings::route('/'),
        ];
    }
}
