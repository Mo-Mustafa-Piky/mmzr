<?php

namespace App\Filament\Resources\Homepages;

use App\Filament\Resources\Homepages\Pages\CreateHomepage;
use App\Filament\Resources\Homepages\Pages\EditHomepage;
use App\Filament\Resources\Homepages\Pages\ListHomepages;
use App\Filament\Resources\Homepages\Schemas\HomepageForm;
use App\Filament\Resources\Homepages\Tables\HomepagesTable;
use App\Models\Homepage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class HomepageResource extends Resource
{
    protected static ?string $model = Homepage::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static string | UnitEnum | null $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return HomepageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomepagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => EditHomepage::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
