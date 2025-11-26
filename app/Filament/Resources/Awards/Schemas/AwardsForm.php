<?php

namespace App\Filament\Resources\Awards\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class AwardsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Award')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('award_title')->required()->maxLength(255),
                                TextInput::make('award_organization')->required()->maxLength(255),
                                TextInput::make('year')->required()->maxLength(255),
                                TextInput::make('badge_text')->maxLength(255),
                                Textarea::make('description')->rows(3),
                                SpatieMediaLibraryFileUpload::make('award_image')
                                    ->collection('award_image')
                                    ->image()
                                    ->maxSize(5120),
                                TextInput::make('order')->numeric()->default(0),
                                Toggle::make('is_active')->default(true),
                                Toggle::make('is_featured')->default(false),
                            ])->columns(2),
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('meta_title')->maxLength(255),
                                Textarea::make('meta_description')->rows(3),
                                TextInput::make('meta_keywords')->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
