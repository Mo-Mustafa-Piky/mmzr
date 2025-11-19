<?php

namespace App\Filament\Resources\UnitViews;

use App\Filament\Resources\UnitViews\Pages\ListUnitViews;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class UnitViewResource extends Resource
{
    protected static ?string $model = \App\Models\UnitView::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Unit Views';

    public static function getPages(): array
    {
        return [
            'index' => ListUnitViews::route('/'),
        ];
    }
}