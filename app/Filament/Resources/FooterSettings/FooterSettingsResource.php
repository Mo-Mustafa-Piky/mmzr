<?php

namespace App\Filament\Resources\FooterSettings;

use App\Filament\Resources\FooterSettings\Pages\EditFooterSettings;
use App\Filament\Resources\FooterSettings\Schemas\FooterSettingsForm;
use App\Models\FooterSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;

class FooterSettingsResource extends Resource
{
    protected static ?string $model = FooterSettings::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | UnitEnum | null $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Footer Settings';

    public static function form(Schema $schema): Schema
    {
        return FooterSettingsForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditFooterSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
