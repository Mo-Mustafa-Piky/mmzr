<?php

namespace App\Filament\Resources\UpdatedUnitsSales\Pages;

use App\Filament\Resources\UpdatedUnitsSales\UpdatedUnitsSalesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUpdatedUnitsSales extends EditRecord
{
    protected static string $resource = UpdatedUnitsSalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
