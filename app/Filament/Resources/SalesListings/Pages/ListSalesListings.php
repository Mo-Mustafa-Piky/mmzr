<?php

namespace App\Filament\Resources\SalesListings\Pages;

use App\Filament\Resources\SalesListings\SalesListingsResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListSalesListings extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = SalesListingsResource::class;
    protected string $view = 'filament.resources.sales-listings.pages.list-sales-listings';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_sales_listings');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getSalesListingsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('Images')
                    ->label('Image')
                    ->getStateUsing(fn($record) => $record['Images']['Image'][0]['ImageURL'] ?? null)
                    ->size(60),
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('RefNo')
                    ->label('Ref No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('PropertyName')
                    ->label('Property')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Category')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('Status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'Vacant' => 'success',
                        'Occupied' => 'warning',
                        default => 'gray'
                    }),
                TextColumn::make('SellPrice')
                    ->label('Price')
                    ->money('AED')
                    ->sortable(),
                TextColumn::make('Bedrooms')
                    ->label('Beds')
                    ->sortable(),
                TextColumn::make('NoOfBathrooms')
                    ->label('Baths')
                    ->sortable(),
                TextColumn::make('BuiltupArea')
                    ->label('Built-up Area')
                    ->sortable(),
                TextColumn::make('PlotArea')
                    ->label('Plot Area')
                    ->sortable(),
                TextColumn::make('Community')
                    ->label('Community')
                    ->searchable(),
                TextColumn::make('SubCommunity')
                    ->label('Sub Community')
                    ->searchable(),
                TextColumn::make('CityName')
                    ->label('City')
                    ->searchable(),
                TextColumn::make('StateName')
                    ->label('State')
                    ->searchable(),
                TextColumn::make('CountryName')
                    ->label('Country')
                    ->searchable(),
                TextColumn::make('Agent')
                    ->label('Agent')
                    ->searchable(),
                TextColumn::make('ContactNumber')
                    ->label('Contact')
                    ->searchable(),
                TextColumn::make('MarketingTitle')
                    ->label('Marketing Title')
                    ->limit(50),
                TextColumn::make('LastUpdated')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('LastUpdated', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('CountryID')
                    ->label('Country')
                    ->options($this->getCountryOptions()),
                \Filament\Tables\Filters\SelectFilter::make('StateID')
                    ->label('State')
                    ->options($this->getStateOptions()),
                \Filament\Tables\Filters\SelectFilter::make('CommunityID')
                    ->label('Community')
                    ->options($this->getCommunityOptions()),
                \Filament\Tables\Filters\SelectFilter::make('Category')
                    ->options($this->getCategoryOptions()),
                \Filament\Tables\Filters\SelectFilter::make('Status')
                    ->options($this->getStatusOptions()),
                \Filament\Tables\Filters\SelectFilter::make('Bedrooms')
                    ->label('Bedrooms')
                    ->options($this->getBedroomOptions()),
                \Filament\Tables\Filters\Filter::make('SellPrice')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('price_from')->numeric(),
                        \Filament\Forms\Components\TextInput::make('price_to')->numeric(),
                    ]),
            ])
            ->searchable();
    }

    protected function getSalesListingsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_sales_listings', function () {
            return app(GoyzerService::class)->getSalesListings();
        });
        
        if (!$result || !isset($result['UnitDTO'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $listings = is_array($result['UnitDTO']) && isset($result['UnitDTO'][0]) 
            ? $result['UnitDTO'] 
            : [$result['UnitDTO']];

        $collection = collect($listings)
            ->filter(fn($listing) => empty($listing['ErrorMessage'] ?? ''))
            ->mapWithKeys(function ($listing, $index) {
                return [$listing['Code'] ?? $index => $listing];
            });

        if (filled($search)) {
            $collection = $collection->filter(function ($listing) use ($search) {
                return str_contains(strtolower($listing['Code'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($listing['PropertyName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($listing['Community'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['CountryID']['value'] ?? null)) {
            $collection = $collection->where('CountryID', $filters['CountryID']['value']);
        }

        if (filled($filters['StateID']['value'] ?? null)) {
            $collection = $collection->where('StateID', $filters['StateID']['value']);
        }

        if (filled($filters['CommunityID']['value'] ?? null)) {
            $collection = $collection->where('CommunityID', $filters['CommunityID']['value']);
        }

        if (filled($filters['Category']['value'] ?? null)) {
            $collection = $collection->where('Category', $filters['Category']['value']);
        }

        if (filled($filters['Status']['value'] ?? null)) {
            $collection = $collection->where('Status', $filters['Status']['value']);
        }

        if (filled($filters['Bedrooms']['value'] ?? null)) {
            $collection = $collection->where('Bedrooms', $filters['Bedrooms']['value']);
        }

        if (filled($filters['SellPrice']['price_from'] ?? null)) {
            $collection = $collection->filter(fn($listing) => ($listing['SellPrice'] ?? 0) >= $filters['SellPrice']['price_from']);
        }

        if (filled($filters['SellPrice']['price_to'] ?? null)) {
            $collection = $collection->filter(fn($listing) => ($listing['SellPrice'] ?? 0) <= $filters['SellPrice']['price_to']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
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

    protected function getBedroomOptions(): array
    {
        return [
            '0' => 'Studio',
            '1' => '1 Bedroom',
            '2' => '2 Bedrooms',
            '3' => '3 Bedrooms',
            '4' => '4 Bedrooms',
            '5' => '5+ Bedrooms',
        ];
    }

    protected function getStateOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_states', function () {
            return app(GoyzerService::class)->getStates();
        });
        
        if (!$result || !isset($result['GetStatesData'])) return [];
        
        $states = is_array($result['GetStatesData']) && isset($result['GetStatesData'][0]) ? $result['GetStatesData'] : [$result['GetStatesData']];
        
        $options = [];
        foreach ($states as $state) {
            $options[$state['StateID'] ?? ''] = $state['StateName'] ?? '';
        }
        return $options;
    }

    protected function getCommunityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_communities', function () {
            return app(GoyzerService::class)->getCommunities();
        });
        
        if (!$result || !isset($result['GetCommunitiesData'])) return [];
        
        $communities = is_array($result['GetCommunitiesData']) && isset($result['GetCommunitiesData'][0]) ? $result['GetCommunitiesData'] : [$result['GetCommunitiesData']];
        
        $options = [];
        foreach ($communities as $community) {
            $options[$community['CommunityID'] ?? ''] = $community['Community'] ?? '';
        }
        return $options;
    }

    protected function getCategoryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_sales_listings', function () {
            return app(GoyzerService::class)->getSalesListings();
        });
        
        if (!$result || !isset($result['UnitDTO'])) return [];
        
        $listings = is_array($result['UnitDTO']) && isset($result['UnitDTO'][0]) ? $result['UnitDTO'] : [$result['UnitDTO']];
        
        return collect($listings)
            ->pluck('Category')
            ->filter()
            ->unique()
            ->sort()
            ->mapWithKeys(fn($cat) => [$cat => $cat])
            ->toArray();
    }

    protected function getStatusOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_sales_listings', function () {
            return app(GoyzerService::class)->getSalesListings();
        });
        
        if (!$result || !isset($result['UnitDTO'])) return [];
        
        $listings = is_array($result['UnitDTO']) && isset($result['UnitDTO'][0]) ? $result['UnitDTO'] : [$result['UnitDTO']];
        
        return collect($listings)
            ->pluck('Status')
            ->filter()
            ->unique()
            ->sort()
            ->mapWithKeys(fn($status) => [$status => $status])
            ->toArray();
    }
}
