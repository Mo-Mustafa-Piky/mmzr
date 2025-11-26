<?php

namespace App\Filament\Resources\AreaGuides\Pages;

use App\Filament\Resources\AreaGuides\AreaGuidesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAreaGuide extends EditRecord
{
    protected static string $resource = AreaGuidesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
