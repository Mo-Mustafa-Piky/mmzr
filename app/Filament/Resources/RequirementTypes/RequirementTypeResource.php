<?php

namespace App\Filament\Resources\RequirementTypes;

use App\Filament\Resources\RequirementTypes\Pages\ListRequirementTypes;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class RequirementTypeResource extends Resource
{
    protected static ?string $model = \App\Models\RequirementType::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Requirement Types';

    public static function getPages(): array
    {
        return [
            'index' => ListRequirementTypes::route('/'),
        ];
    }
}