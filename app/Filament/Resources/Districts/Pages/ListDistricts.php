<?php

namespace App\Filament\Resources\Districts\Pages;

use App\Filament\Resources\Districts\DistrictResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListDistricts extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = DistrictResource::class;
    protected string $view = 'filament.resources.districts.pages.list-districts';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_districts');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getDistrictsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('DistrictID')
                    ->label('District ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('DistrictName')
                    ->label('District Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CityName')
                    ->label('City')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getCityName($record['CityID'] ?? '');
                    }),
            ])
            ->filters([
                SelectFilter::make('CityID')
                    ->label('City')
                    ->options($this->getCityOptions()),
            ])
            ->searchable();
    }

    protected function getDistrictsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_districts', 3600, function () {
            return app(GoyzerService::class)->getDistricts();
        });
        
        if (!$result || !isset($result['GetDistrictData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $districts = is_array($result['GetDistrictData']) && isset($result['GetDistrictData'][0]) 
            ? $result['GetDistrictData'] 
            : [$result['GetDistrictData']];

        $collection = collect($districts)->mapWithKeys(function ($district, $index) {
            return [$district['DistrictID'] ?? $index => $district];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($district) use ($search) {
                return str_contains(strtolower($district['DistrictID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($district['DistrictName'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['CityID']['value'] ?? null)) {
            $collection = $collection->where('CityID', $filters['CityID']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }

    protected function getCityName(string $cityId): string
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_cities', 3600, function () {
            return app(GoyzerService::class)->getCities();
        });
        
        if (!$result || !isset($result['GetCityData'])) {
            return $cityId;
        }

        $cities = is_array($result['GetCityData']) && isset($result['GetCityData'][0]) 
            ? $result['GetCityData'] 
            : [$result['GetCityData']];

        foreach ($cities as $city) {
            if (($city['CityID'] ?? '') === $cityId) {
                return $city['CityName'] ?? $cityId;
            }
        }

        return $cityId;
    }

    protected function getCityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_cities', 3600, function () {
            return app(GoyzerService::class)->getCities();
        });
        
        if (!$result || !isset($result['GetCityData'])) {
            return [];
        }

        $cities = is_array($result['GetCityData']) && isset($result['GetCityData'][0]) 
            ? $result['GetCityData'] 
            : [$result['GetCityData']];

        $options = [];
        foreach ($cities as $city) {
            $options[$city['CityID'] ?? ''] = $city['CityName'] ?? '';
        }

        return $options;
    }
}