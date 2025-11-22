<?php

namespace App\Filament\Resources\UpdatedProjects\Pages;

use App\Filament\Resources\UpdatedProjects\UpdatedProjectsResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListUpdatedProjects extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = UpdatedProjectsResource::class;
    protected string $view = 'filament.resources.updated-projects.pages.list-updated-projects';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_updated_projects');
                    Notification::make()
                        ->title('Cache cleared successfully')
                        ->success()
                        ->send();
                })
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getUpdatedProjectsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('Code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CountryName')
                    ->label('Country')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getCountryName($record['CountryID'] ?? '');
                    }),
                TextColumn::make('ServiceName')
                    ->label('Service')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getServiceName($record['ServiceID'] ?? '');
                    }),
                TextColumn::make('CategoryName')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getCategoryName($record['CategoryID'] ?? '');
                    }),
                TextColumn::make('LastUpdated')
                    ->label('Last Updated')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Deleted')
                    ->label('Deleted')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'true' ? 'danger' : 'success'),
                TextColumn::make('AddToWeb')
                    ->label('Add To Web')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'true' ? 'success' : 'gray'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('CountryID')
                    ->label('Country')
                    ->options($this->getCountryOptions()),
                \Filament\Tables\Filters\SelectFilter::make('ServiceID')
                    ->label('Service')
                    ->options($this->getServiceOptions()),
                \Filament\Tables\Filters\SelectFilter::make('CategoryID')
                    ->label('Category')
                    ->options($this->getCategoryOptions()),
            ])
            ->searchable();
    }

    protected function getUpdatedProjectsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_updated_projects', function () {
            return app(GoyzerService::class)->getProjectsLastUpdated();
        });
        
        if (!$result || !isset($result['UpdatedUnitList'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $projects = is_array($result['UpdatedUnitList']) && isset($result['UpdatedUnitList'][0]) 
            ? $result['UpdatedUnitList'] 
            : [$result['UpdatedUnitList']];

        $collection = collect($projects)->mapWithKeys(function ($project, $index) {
            return [$project['Code'] ?? $index => $project];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($project) use ($search) {
                return str_contains(strtolower($project['Code'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($project['CountryID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($project['ServiceID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($project['CategoryID'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['CountryID']['value'] ?? null)) {
            $collection = $collection->where('CountryID', $filters['CountryID']['value']);
        }

        if (filled($filters['ServiceID']['value'] ?? null)) {
            $collection = $collection->where('ServiceID', $filters['ServiceID']['value']);
        }

        if (filled($filters['CategoryID']['value'] ?? null)) {
            $collection = $collection->where('CategoryID', $filters['CategoryID']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }

    protected function getCountryName(string $countryId): string
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_countries', function () {
            return app(GoyzerService::class)->getCountry();
        });
        
        if (!$result || !isset($result['GetCountryData'])) return $countryId;
        
        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) ? $result['GetCountryData'] : [$result['GetCountryData']];
        
        foreach ($countries as $country) {
            if (($country['CountryID'] ?? '') === $countryId) return $country['CountryName'] ?? $countryId;
        }
        return $countryId;
    }

    protected function getServiceName(string $serviceId): string
    {
        $services = [
            '1' => 'Sale',
            '2' => 'Rent',
        ];
        return $services[$serviceId] ?? $serviceId;
    }

    protected function getCategoryName(string $categoryId): string
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_unit_categories', function () {
            return app(GoyzerService::class)->getUnitCategory();
        });
        
        if (!$result || !isset($result['GetUnitCategoryData'])) return $categoryId;
        
        $categories = is_array($result['GetUnitCategoryData']) && isset($result['GetUnitCategoryData'][0]) ? $result['GetUnitCategoryData'] : [$result['GetUnitCategoryData']];
        
        foreach ($categories as $category) {
            if (($category['CategoryID'] ?? '') === $categoryId) return $category['CategoryName'] ?? $categoryId;
        }
        return $categoryId;
    }

    protected function getCountryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_countries', function () {
            return app(GoyzerService::class)->getCountry();
        });
        
        if (!$result || !isset($result['GetCountryData'])) return [];
        
        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) ? $result['GetCountryData'] : [$result['GetCountryData']];
        
        $options = [];
        foreach ($countries as $country) {
            $options[$country['CountryID'] ?? ''] = $country['CountryName'] ?? '';
        }
        return $options;
    }

    protected function getServiceOptions(): array
    {
        return [
            '1' => 'Sale',
            '2' => 'Rent',
        ];
    }

    protected function getCategoryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_unit_categories', function () {
            return app(GoyzerService::class)->getUnitCategory();
        });
        
        if (!$result || !isset($result['GetUnitCategoryData'])) return [];
        
        $categories = is_array($result['GetUnitCategoryData']) && isset($result['GetUnitCategoryData'][0]) ? $result['GetUnitCategoryData'] : [$result['GetUnitCategoryData']];
        
        $options = [];
        foreach ($categories as $category) {
            $options[$category['CategoryID'] ?? ''] = $category['CategoryName'] ?? '';
        }
        return $options;
    }
}
