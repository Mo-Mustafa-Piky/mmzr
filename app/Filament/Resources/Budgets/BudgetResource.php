<?php

namespace App\Filament\Resources\Budgets;

use App\Filament\Resources\Budgets\Pages\ListBudgets;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BudgetResource extends Resource
{
    protected static ?string $model = \App\Models\Budget::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Budgets';

    public static function getPages(): array
    {
        return [
            'index' => ListBudgets::route('/'),
        ];
    }
}