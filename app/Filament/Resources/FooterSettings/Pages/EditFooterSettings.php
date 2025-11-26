<?php

namespace App\Filament\Resources\FooterSettings\Pages;

use App\Filament\Resources\FooterSettings\FooterSettingsResource;
use App\Models\FooterSettings;
use Filament\Resources\Pages\EditRecord;

class EditFooterSettings extends EditRecord
{
    protected static string $resource = FooterSettingsResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = FooterSettings::firstOrCreate(['id' => 1]);
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
