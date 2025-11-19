<?php

namespace App\Filament\Resources\UpdatedUnitsSales\Pages;

use App\Filament\Resources\UpdatedUnitsSales\UpdatedUnitsSalesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUpdatedUnitsSales extends ViewRecord
{
    protected static string $resource = UpdatedUnitsSalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
