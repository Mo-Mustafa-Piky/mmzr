<?php

namespace App\Filament\Resources\AwardsBanner\Pages;

use App\Filament\Resources\AwardsBanner\AwardsBannerResource;
use App\Models\AwardsBanner;
use Filament\Resources\Pages\EditRecord;

class EditAwardsBanner extends EditRecord
{
    protected static string $resource = AwardsBannerResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = AwardsBanner::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Awards are at the heart of what we do',
                'button_one_text' => 'Work With Us',
                'button_two_text' => 'Our Story',
                'is_active' => true,
            ]
        );
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
