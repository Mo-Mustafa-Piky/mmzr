<?php

namespace App\Filament\Resources\BlogsBanners;

use App\Filament\Resources\BlogsBanners\Pages\CreateBlogsBanner;
use App\Filament\Resources\BlogsBanners\Pages\EditBlogsBanner;
use App\Filament\Resources\BlogsBanners\Pages\ListBlogsBanners;
use App\Filament\Resources\BlogsBanners\Schemas\BlogsBannerForm;
use App\Filament\Resources\BlogsBanners\Tables\BlogsBannersTable;
use App\Models\BlogsBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BlogsBannerResource extends Resource
{
    protected static ?string $model = BlogsBanner::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-photo';
    protected static string | UnitEnum | null $navigationGroup = 'Blogs Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Banner Settings';

    public static function form(Schema $schema): Schema
    {
        return BlogsBannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogsBannersTable::configure($table);
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
            'index' => EditBlogsBanner::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
