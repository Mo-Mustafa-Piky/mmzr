<?php

namespace App\Filament\Resources\BudgetsByCountry;

use App\Filament\Resources\BudgetsByCountry\Pages\ListBudgetsByCountry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BudgetByCountryResource extends Resource
{
    protected static ?string $model = \App\Models\BudgetByCountry::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Budgets by Country';

    public static function getPages(): array
    {
        return [
            'index' => ListBudgetsByCountry::route('/'),
        ];
    }
}