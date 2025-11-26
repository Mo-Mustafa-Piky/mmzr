<?php

namespace App\Filament\Resources\AreaGuides\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AreaGuidesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Area Guide')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('area_name')->required()->maxLength(255)->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                                Textarea::make('description')->required()->rows(3),
                                SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->collection('featured_image')
                                    ->image()
                                    ->maxSize(5120),
                                TextInput::make('badge_text')->default('Updated Read')->maxLength(255),
                                TextInput::make('button_text')->default('Read Guide')->maxLength(255),
                                TextInput::make('order')->numeric()->default(0),
                                Toggle::make('is_active')->default(true),
                            ])->columns(2),
                        Tabs\Tab::make('Full Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                RichEditor::make('full_content')->columnSpanFull(),
                            ]),
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
