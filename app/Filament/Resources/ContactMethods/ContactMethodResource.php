<?php

namespace App\Filament\Resources\ContactMethods;

use App\Filament\Resources\ContactMethods\Pages\ListContactMethods;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ContactMethodResource extends Resource
{
    protected static ?string $model = \App\Models\ContactMethod::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Contact Methods';

    public static function getPages(): array
    {
        return [
            'index' => ListContactMethods::route('/'),
        ];
    }
}