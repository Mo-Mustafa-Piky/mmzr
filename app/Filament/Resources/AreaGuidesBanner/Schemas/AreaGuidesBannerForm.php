<?php

namespace App\Filament\Resources\AreaGuidesBanner\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AreaGuidesBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Banner Content')
                    ->schema([
                        TextInput::make('title')->required()->maxLength(255),
                        SpatieMediaLibraryFileUpload::make('background_image')
                            ->collection('background_image')
                            ->image()
                            ->maxSize(5120),
                    ]),
                Section::make('Download Button')
                    ->schema([
                        TextInput::make('download_button_text')->default('Download')->maxLength(255),
                        TextInput::make('download_link')->url()->maxLength(255),
                    ])->columns(2),
                Section::make('Video Button')
                    ->schema([
                        TextInput::make('video_button_text')->default('Watch Video')->maxLength(255),
                        TextInput::make('video_link')->url()->maxLength(255),
                    ])->columns(2),
                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
