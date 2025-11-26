<?php

namespace App\Filament\Resources\AreaGuides\Pages;

use App\Filament\Resources\AreaGuides\AreaGuidesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAreaGuides extends ListRecords
{
    protected static string $resource = AreaGuidesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
