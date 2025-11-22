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

class DirectMarketingsRelationManager extends RelationManager
{
    protected static string $relationship = 'directMarketings';
    protected static ?string $title = 'Direct Marketing';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('update_values')->label('Update Values')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('update_values')->label('Update Values'),
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
