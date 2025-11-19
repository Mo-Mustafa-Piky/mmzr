<?php

namespace App\Filament\Resources\Cities\Pages;

use App\Filament\Resources\Cities\CityResource;
use App\Services\GoyzerService;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListCities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = CityResource::class;
    protected string $view = 'filament.resources.cities.pages.list-cities';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_cities');
                    \Filament\Notifications\Notification::make()
                        ->title('Cache cleared successfully')
                        ->success()
                        ->send();
                })
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getCitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('CityID')
                    ->label('City ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CityName')
                    ->label('City Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('StateName')
                    ->label('State')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getStateName($record['StateID'] ?? '');
                    }),
            ])
            ->filters([
                SelectFilter::make('StateID')
                    ->label('State')
                    ->options($this->getStateOptions()),
            ])
            ->searchable();
    }

    protected function getCitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_cities', 3600, function () {
            $goyzerService = app(GoyzerService::class);
            return $goyzerService->getCities();
        });
        
        if (!$result || !isset($result['GetCityData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $cities = is_array($result['GetCityData']) && isset($result['GetCityData'][0]) 
            ? $result['GetCityData'] 
            : [$result['GetCityData']];

        $collection = collect($cities)->mapWithKeys(function ($city, $index) {
            return [$city['CityID'] ?? $index => $city];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($city) use ($search) {
                return str_contains(strtolower($city['CityID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($city['CityName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($city['StateID'] ?? ''), strtolower($search));
            });
        }

        // Apply filters
        if (filled($filters['StateID']['value'] ?? null)) {
            $collection = $collection->where('StateID', $filters['StateID']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator(
            $paginatedData,
            $total,
            $recordsPerPage,
            $page
        );
    }

    protected function getStateName(string $stateId): string
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_states', 3600, function () {
            return app(GoyzerService::class)->getStates();
        });
        
        if (!$result || !isset($result['GetStateData'])) return $stateId;
        
        $states = is_array($result['GetStateData']) && isset($result['GetStateData'][0]) ? $result['GetStateData'] : [$result['GetStateData']];
        
        foreach ($states as $state) {
            if (($state['StateID'] ?? '') === $stateId) return $state['StateName'] ?? $stateId;
        }
        return $stateId;
    }

    protected function getStateOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_states', 3600, function () {
            return app(GoyzerService::class)->getStates();
        });
        
        if (!$result || !isset($result['GetStateData'])) return [];
        
        $states = is_array($result['GetStateData']) && isset($result['GetStateData'][0]) ? $result['GetStateData'] : [$result['GetStateData']];
        
        $options = [];
        foreach ($states as $state) {
            $options[$state['StateID'] ?? ''] = $state['StateName'] ?? '';
        }
        return $options;
    }
}