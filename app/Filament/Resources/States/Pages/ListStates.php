<?php

namespace App\Filament\Resources\States\Pages;

use App\Filament\Resources\States\StateResource;
use App\Services\GoyzerService;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListStates extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = StateResource::class;
    protected string $view = 'filament.resources.states.pages.list-states';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getStatesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('StateID')
                    ->label('State ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('StateName')
                    ->label('State Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CountryName')
                    ->label('Country')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getCountryName($record['CountryID'] ?? '');
                    }),
            ])
            ->filters([
                SelectFilter::make('CountryID')
                    ->label('Country')
                    ->options($this->getCountryOptions()),
            ])
            ->searchable();
    }

    protected function getStatesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getStates();
        
        if (!$result || !isset($result['GetStateData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $states = is_array($result['GetStateData']) && isset($result['GetStateData'][0]) 
            ? $result['GetStateData'] 
            : [$result['GetStateData']];

        $collection = collect($states)->mapWithKeys(function ($state, $index) {
            return [$state['StateID'] ?? $index => $state];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($state) use ($search) {
                return str_contains(strtolower($state['StateID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($state['StateName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($state['CountryID'] ?? ''), strtolower($search));
            });
        }

        // Apply filters
        if (filled($filters['CountryID']['value'] ?? null)) {
            $collection = $collection->where('CountryID', $filters['CountryID']['value']);
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

    protected function getCountryName(string $countryId): string
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getCountry();
        
        if (!$result || !isset($result['GetCountryData'])) {
            return $countryId;
        }

        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) 
            ? $result['GetCountryData'] 
            : [$result['GetCountryData']];

        foreach ($countries as $country) {
            if (($country['CountryID'] ?? '') === $countryId) {
                return $country['CountryName'] ?? $countryId;
            }
        }

        return $countryId;
    }

    protected function getCountryOptions(): array
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getCountry();
        
        if (!$result || !isset($result['GetCountryData'])) {
            return [];
        }

        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) 
            ? $result['GetCountryData'] 
            : [$result['GetCountryData']];

        $options = [];
        foreach ($countries as $country) {
            $options[$country['CountryID'] ?? ''] = $country['CountryName'] ?? '';
        }

        return $options;
    }
}