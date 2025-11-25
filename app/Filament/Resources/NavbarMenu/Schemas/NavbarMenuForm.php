<?php

namespace App\Filament\Resources\NavbarMenu\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class NavbarMenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Navbar Menu')
                    ->tabs([
                        Tabs\Tab::make('Menu Items')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Repeater::make('menu_items')
                                    ->schema([
                                        TextInput::make('label')->required()->label('Label'),
                                        TextInput::make('url')->required()->label('URL'),
                                        Toggle::make('is_active')->default(true)->label('Active'),
                                        Toggle::make('open_in_new_tab')->default(false)->label('Open in New Tab'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->collapsible(),
                            ]),
                        Tabs\Tab::make('Call to Action')
                            ->icon('heroicon-o-cursor-arrow-rays')
                            ->schema([
                                TextInput::make('cta_label')->label('CTA Label'),
                                TextInput::make('cta_url')->label('CTA URL'),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
