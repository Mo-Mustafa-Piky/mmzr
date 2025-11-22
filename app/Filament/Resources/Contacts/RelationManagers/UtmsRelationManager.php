<?php

namespace App\Filament\Resources\Contacts\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UtmsRelationManager extends RelationManager
{
    protected static string $relationship = 'utms';
    protected static ?string $title = 'UTM Parameters';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('utm_id')->label('UTM ID')->maxLength(255),
                TextInput::make('utm_term')->label('UTM Term')->maxLength(255),
                TextInput::make('utm_content')->label('UTM Content')->maxLength(255),
                TextInput::make('utm_ad_set')->label('UTM Ad Set')->maxLength(255),
                TextInput::make('utm_campaign_name')->label('UTM Campaign Name')->maxLength(255),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('utm_id')->label('UTM ID'),
                TextColumn::make('utm_term')->label('UTM Term'),
                TextColumn::make('utm_content')->label('UTM Content'),
                TextColumn::make('utm_ad_set')->label('UTM Ad Set'),
                TextColumn::make('utm_campaign_name')->label('Campaign Name'),
                TextColumn::make('created_at')->dateTime()->label('Created'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
