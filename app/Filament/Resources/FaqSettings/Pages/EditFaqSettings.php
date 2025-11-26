<?php

namespace App\Filament\Resources\FaqSettings\Pages;

use App\Filament\Resources\FaqSettings\FaqSettingsResource;
use App\Models\FaqSettings;
use Filament\Resources\Pages\EditRecord;

class EditFaqSettings extends EditRecord
{
    protected static string $resource = FaqSettingsResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = FaqSettings::firstOrCreate(['id' => 1]);
        $this->fillForm();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }
}
