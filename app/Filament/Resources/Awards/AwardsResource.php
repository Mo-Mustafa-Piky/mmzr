<?php

namespace App\Filament\Resources\Awards;

use App\Filament\Resources\Awards\Pages\CreateAward;
use App\Filament\Resources\Awards\Pages\EditAward;
use App\Filament\Resources\Awards\Pages\ListAwards;
use App\Filament\Resources\Awards\Schemas\AwardsForm;
use App\Filament\Resources\Awards\Tables\AwardsTable;
use App\Models\Award;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AwardsResource extends Resource
{
    protected static ?string $model = Award::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-star';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Awards';

    public static function form(Schema $schema): Schema
    {
        return AwardsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AwardsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAwards::route('/'),
            'create' => CreateAward::route('/create'),
            'edit' => EditAward::route('/{record}/edit'),
        ];
    }
}
