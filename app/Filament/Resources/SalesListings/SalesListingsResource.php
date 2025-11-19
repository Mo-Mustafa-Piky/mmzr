<?php

namespace App\Filament\Resources\SalesListings;

use App\Filament\Resources\SalesListings\Pages;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SalesListingsResource extends Resource
{
    protected static ?string $navigationLabel = 'Sales Listings';
    protected static ?string $modelLabel = 'Sales Listing';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?int $navigationSort = 103;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('PropertyName')
                    ->label('Property')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Price')
                    ->label('Price')
                    ->money('AED')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Bedrooms')
                    ->label('Bedrooms')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Community')
                    ->label('Community')
                    ->searchable(),
            ])
            ->defaultSort('Code', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesListings::route('/'),
        ];
    }
}
