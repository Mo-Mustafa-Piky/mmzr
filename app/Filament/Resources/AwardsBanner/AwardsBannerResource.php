<?php

namespace App\Filament\Resources\AwardsBanner;

use App\Filament\Resources\AwardsBanner\Pages\EditAwardsBanner;
use App\Filament\Resources\AwardsBanner\Schemas\AwardsBannerForm;
use App\Models\AwardsBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;

class AwardsBannerResource extends Resource
{
    protected static ?string $model = AwardsBanner::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-trophy';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Awards Banner';

    public static function form(Schema $schema): Schema
    {
        return AwardsBannerForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditAwardsBanner::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
