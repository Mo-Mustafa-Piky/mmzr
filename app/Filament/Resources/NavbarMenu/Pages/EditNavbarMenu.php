<?php

namespace App\Filament\Resources\NavbarMenu\Pages;

use App\Filament\Resources\NavbarMenu\NavbarMenuResource;
use App\Models\NavbarMenu;
use Filament\Resources\Pages\EditRecord;

class EditNavbarMenu extends EditRecord
{
    protected static string $resource = NavbarMenuResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = NavbarMenu::firstOrCreate(['id' => 1]);
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
