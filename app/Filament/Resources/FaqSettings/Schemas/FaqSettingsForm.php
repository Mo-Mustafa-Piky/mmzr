<?php

namespace App\Filament\Resources\FaqSettings\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQs')
                ->columnSpanFull()
                    ->schema([
                        Repeater::make('faqs')
                        ->columnSpanFull()
                            ->schema([
                                TextInput::make('question')->required()->columnSpanFull(),
                                RichEditor::make('answer')->required()->columnSpanFull(),
                                TextInput::make('order')->numeric()->default(0),
                                Checkbox::make('is_active')->default(true) ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
