<?php

namespace App\Filament\Resources\UpdatedProjects;

use App\Filament\Resources\UpdatedProjects\Pages;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class UpdatedProjectsResource extends Resource
{
    protected static ?string $navigationLabel = 'Updated Projects';
    protected static ?string $modelLabel = 'Updated Project';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?int $navigationSort = 102;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CountryID')
                    ->label('Country ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ServiceID')
                    ->label('Service ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CategoryID')
                    ->label('Category ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('LastUpdated')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Deleted')
                    ->label('Deleted')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'true' ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('AddToWeb')
                    ->label('Add To Web')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'true' ? 'success' : 'gray'),
            ])
            ->defaultSort('LastUpdated', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpdatedProjects::route('/'),
        ];
    }
}
