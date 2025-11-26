<?php

namespace App\Filament\Resources\FooterSettings\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class FooterSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Footer Settings')
                    ->tabs([
                        Tabs\Tab::make('Logo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('footer_logo')
                                    ->collection('footer_logo')
                                    ->image()
                                    ->maxSize(5120),
                            ]),
                        Tabs\Tab::make('Column 1')
                            ->schema([
                                TextInput::make('column_1_title')->default('Explore'),
                                Repeater::make('column_1_items')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                    ])
                                    ->columns(2),
                                Toggle::make('column_1_is_active')->default(true),
                            ]),
                        Tabs\Tab::make('Column 2')
                            ->schema([
                                TextInput::make('column_2_title')->default('Services'),
                                Repeater::make('column_2_items')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                    ])
                                    ->columns(2),
                                Toggle::make('column_2_is_active')->default(true),
                            ]),
                        Tabs\Tab::make('Column 3')
                            ->schema([
                                TextInput::make('column_3_title')->default('Insights'),
                                Repeater::make('column_3_items')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                    ])
                                    ->columns(2),
                                Toggle::make('column_3_is_active')->default(true),
                            ]),
                        Tabs\Tab::make('Column 4')
                            ->schema([
                                TextInput::make('column_4_title')->default('About'),
                                Repeater::make('column_4_items')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                    ])
                                    ->columns(2),
                                Toggle::make('column_4_is_active')->default(true),
                            ]),
                        Tabs\Tab::make('Column 5')
                            ->schema([
                                TextInput::make('column_5_title'),
                                Repeater::make('column_5_items')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('url')->required(),
                                    ])
                                    ->columns(2),
                                Toggle::make('column_5_is_active')->default(false),
                            ]),
                        Tabs\Tab::make('Footer Bottom')
                            ->schema([
                                TextInput::make('copyright_text')->default('Copyright © 2025 Mamzr. All Rights Reserved.'),
                                TextInput::make('powered_by_text')->default('Powered by PikyHost'),
                                TextInput::make('powered_by_link')->url(),
                                TextInput::make('privacy_link')->url(),
                                TextInput::make('terms_link')->url(),
                                TextInput::make('sitemap_link')->url(),
                                Toggle::make('is_active')->default(true),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
