<?php

namespace App\Filament\Resources\FaqSettings;

use App\Filament\Resources\FaqSettings\Pages\EditFaqSettings;
use App\Filament\Resources\FaqSettings\Schemas\FaqSettingsForm;
use App\Models\FaqSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;

class FaqSettingsResource extends Resource
{
    protected static ?string $model = FaqSettings::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'FAQs';

    public static function form(Schema $schema): Schema
    {
        return FaqSettingsForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditFaqSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
