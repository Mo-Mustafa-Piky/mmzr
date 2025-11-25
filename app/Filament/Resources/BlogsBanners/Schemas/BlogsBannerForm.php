<?php

namespace App\Filament\Resources\BlogsBanners\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BlogsBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Banner Content')
                    ->description('Configure the main banner heading and visibility')
                    ->icon('heroicon-o-document-text')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Banner Title')
                            ->placeholder('The latest news and views at MAMZR')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Show Banner')
                            ->helperText('Toggle to show/hide the banner on the website')
                            ->default(true)
                            ->inline(false),
                    ]),
                Section::make('Background Image')
                    ->description('Upload banner background image')
                    ->icon('heroicon-o-photo')
                    ->columnSpan(1)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('background')
                            ->label('Background')
                            ->collection('background')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '21:9',
                            ])
                            ->maxSize(2048)
                            
                            ->columnSpanFull(),
                    ]),
                Section::make('Call to Action')
                    ->description('Configure the CTA button')
                    ->icon('heroicon-o-cursor-arrow-ripple')
                    ->columnSpan(3)
                    ->columns(2)
                    ->schema([
                        TextInput::make('button_text')
                            ->label('Button Text')
                            ->placeholder('Enquire Now')
                            ->required()
                            ->default('Enquire Now')
                            ->maxLength(50),
                        TextInput::make('button_link')
                            ->label('Button Link')
                            ->placeholder('https://example.com/contact')
                            ->url()
                            ->prefixIcon('heroicon-o-link'),
                    ]),
            ]);
    }
}
