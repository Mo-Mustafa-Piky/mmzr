<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class ManageSettings extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public function mount(int | string $record = null): void
    {
        $this->record = Setting::firstOrCreate([]);
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
