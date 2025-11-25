<?php

namespace App\Filament\Resources\NavbarMenu;

use App\Filament\Resources\NavbarMenu\Pages\EditNavbarMenu;
use App\Filament\Resources\NavbarMenu\Schemas\NavbarMenuForm;
use App\Models\NavbarMenu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;

class NavbarMenuResource extends Resource
{
    protected static ?string $model = NavbarMenu::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-bars-3';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Navbar Menu';

    public static function form(Schema $schema): Schema
    {
        return NavbarMenuForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditNavbarMenu::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
