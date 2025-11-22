<?php

namespace App\Filament\Resources\Contacts\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeedbacksRelationManager extends RelationManager
{
    protected static string $relationship = 'feedbacks';
    protected static ?string $title = 'Agent Feedback';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lead_id')->label('Lead ID')->maxLength(255),
                TextInput::make('feedback_id')->label('Feedback ID')->maxLength(255),
                Textarea::make('remarks')->label('Remarks')->rows(3)->columnSpanFull(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead_id')->label('Lead ID'),
                TextColumn::make('feedback_id')->label('Feedback ID'),
                TextColumn::make('remarks')->label('Remarks')->limit(50),
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
