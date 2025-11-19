<?php

namespace App\Filament\Resources\RentalListings;

use App\Filament\Resources\RentalListings\Pages\ListRentalListings;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class RentalListingsResource extends Resource
{
    protected static ?string $model = \App\Models\RentalListing::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Rental Listings';

    public static function getPages(): array
    {
        return [
            'index' => ListRentalListings::route('/'),
        ];
    }
}
