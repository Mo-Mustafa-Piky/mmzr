<?php

namespace App\Filament\Resources\AreaGuides;

use App\Filament\Resources\AreaGuides\Pages\CreateAreaGuide;
use App\Filament\Resources\AreaGuides\Pages\EditAreaGuide;
use App\Filament\Resources\AreaGuides\Pages\ListAreaGuides;
use App\Filament\Resources\AreaGuides\Schemas\AreaGuidesForm;
use App\Filament\Resources\AreaGuides\Tables\AreaGuidesTable;
use App\Models\AreaGuide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AreaGuidesResource extends Resource
{
    protected static ?string $model = AreaGuide::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-map';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Area Guides';

    public static function form(Schema $schema): Schema
    {
        return AreaGuidesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AreaGuidesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAreaGuides::route('/'),
            'create' => CreateAreaGuide::route('/create'),
            'edit' => EditAreaGuide::route('/{record}/edit'),
        ];
    }
}
