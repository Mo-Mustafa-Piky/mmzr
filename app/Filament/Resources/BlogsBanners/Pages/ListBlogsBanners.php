<?php

namespace App\Filament\Resources\BlogsBanners\Pages;

use App\Filament\Resources\BlogsBanners\BlogsBannerResource;
use App\Models\BlogsBanner;
use Filament\Resources\Pages\ListRecords;

class ListBlogsBanners extends ListRecords
{
    protected static string $resource = BlogsBannerResource::class;

    public function mount(): void
    {
        $banner = BlogsBanner::firstOrCreate(['id' => 1]);
        $this->redirect(BlogsBannerResource::getUrl('edit', ['record' => $banner->id]));
    }
}
