<?php

namespace App\Filament\Resources\Countries\Pages;

use App\Filament\Resources\Countries\CountryResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListCountries extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = CountryResource::class;
    protected string $view = 'filament.resources.countries.pages.list-countries';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_countries');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getCountriesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('CountryID')
                    ->label('Country ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CountryName')
                    ->label('Country Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CountryCode')
                    ->label('Country Code')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getCountriesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_countries', function () {
            $goyzerService = app(GoyzerService::class);
            return $goyzerService->getCountry();
        });
        
        if (!$result || !isset($result['GetCountryData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) 
            ? $result['GetCountryData'] 
            : [$result['GetCountryData']];

        $collection = collect($countries)->mapWithKeys(function ($country, $index) {
            return [$country['CountryID'] ?? $index => $country];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($country) use ($search) {
                return str_contains(strtolower($country['CountryID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($country['CountryName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($country['CountryCode'] ?? ''), strtolower($search));
            });
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
}