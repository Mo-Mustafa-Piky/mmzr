<?php

namespace App\Filament\Resources\AreaGuidesBanner\Pages;

use App\Filament\Resources\AreaGuidesBanner\AreaGuidesBannerResource;
use App\Models\AreaGuidesBanner;
use Filament\Resources\Pages\EditRecord;

class EditAreaGuidesBanner extends EditRecord
{
    protected static string $resource = AreaGuidesBannerResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = AreaGuidesBanner::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Explore the Best Communities to Live in Dubai',
                'download_button_text' => 'Download',
                'video_button_text' => 'Watch Video',
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
