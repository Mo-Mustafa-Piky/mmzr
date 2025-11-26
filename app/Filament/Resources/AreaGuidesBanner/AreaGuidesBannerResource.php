<?php

namespace App\Filament\Resources\AreaGuidesBanner;

use App\Filament\Resources\AreaGuidesBanner\Pages\EditAreaGuidesBanner;
use App\Filament\Resources\AreaGuidesBanner\Schemas\AreaGuidesBannerForm;
use App\Models\AreaGuidesBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;

class AreaGuidesBannerResource extends Resource
{
    protected static ?string $model = AreaGuidesBanner::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-photo';
    protected static string | UnitEnum | null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Area Guides Banner';

    public static function form(Schema $schema): Schema
    {
        return AreaGuidesBannerForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditAreaGuidesBanner::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
