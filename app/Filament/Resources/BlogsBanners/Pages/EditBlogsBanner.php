<?php

namespace App\Filament\Resources\BlogsBanners\Pages;

use App\Filament\Resources\BlogsBanners\BlogsBannerResource;
use App\Models\BlogsBanner;
use Filament\Resources\Pages\EditRecord;

class EditBlogsBanner extends EditRecord
{
    protected static string $resource = BlogsBannerResource::class;

    public function mount(int|string $record = null): void
    {
        $this->record = BlogsBanner::firstOrCreate(
            ['id' => 1],
            ['title' => 'Blog Banner', 'button_text' => 'Enquire Now']
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
