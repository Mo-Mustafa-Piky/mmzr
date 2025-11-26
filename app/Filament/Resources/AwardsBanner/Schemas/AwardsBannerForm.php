<?php

namespace App\Filament\Resources\AwardsBanner\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AwardsBannerForm
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
                Section::make('Button One')
                    ->schema([
                        TextInput::make('button_one_text')->default('Work With Us')->maxLength(255),
                        TextInput::make('button_one_link')->url()->maxLength(255),
                    ])->columns(2),
                Section::make('Button Two')
                    ->schema([
                        TextInput::make('button_two_text')->default('Our Story')->maxLength(255),
                        TextInput::make('button_two_link')->url()->maxLength(255),
                    ])->columns(2),
                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
