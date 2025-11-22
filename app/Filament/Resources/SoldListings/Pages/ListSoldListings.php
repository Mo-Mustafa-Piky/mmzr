<?php

namespace App\Filament\Resources\SoldListings\Pages;

use App\Filament\Resources\SoldListings\SoldListingsResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListSoldListings extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = SoldListingsResource::class;
    protected string $view = 'filament.resources.sold-listings.pages.list-sold-listings';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_sold_listings');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getSoldListingsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('Images')
                    ->label('Image')
                    ->getStateUsing(fn($record) => $record['Images']['Image'][0]['ImageURL'] ?? null)
                    ->size(60),
                TextColumn::make('code')->label('Code')->sortable()->searchable(),
                TextColumn::make('RefNo')->label('Ref No')->sortable()->searchable(),
                TextColumn::make('PropertyName')->label('Property')->sortable()->searchable(),
                TextColumn::make('Category')->label('Category')->sortable(),
                TextColumn::make('Status')->label('Status')->badge()->color('success'),
                TextColumn::make('SellPrice')->label('Sold Price')->money('AED')->sortable(),
                TextColumn::make('Bedrooms')->label('Beds')->sortable()->toggleable(),
                TextColumn::make('NoOfBathrooms')->label('Baths')->sortable()->toggleable(),
                TextColumn::make('BuiltupArea')->label('Area')->sortable(),
                TextColumn::make('Community')->label('Community')->searchable(),
                TextColumn::make('Agent')->label('Agent')->searchable(),
            ])
            ->filters([
                SelectFilter::make('CountryID')
                    ->label('Country')
                    ->options($this->getCountryOptions()),
                SelectFilter::make('StateID')
                    ->label('State')
                    ->options($this->getStateOptions()),
                SelectFilter::make('CommunityID')
                    ->label('Community')
                    ->options($this->getCommunityOptions()),
                SelectFilter::make('Category')
                    ->options($this->getCategoryOptions()),
                SelectFilter::make('Bedrooms')
                    ->label('Bedrooms')
                    ->options([
                        '0' => 'Studio',
                        '1' => '1 Bedroom',
                        '2' => '2 Bedrooms',
                        '3' => '3 Bedrooms',
                        '4' => '4 Bedrooms',
                        '5' => '5+ Bedrooms',
                    ]),
                \Filament\Tables\Filters\Filter::make('SellPrice')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('price_from')->numeric(),
                        \Filament\Forms\Components\TextInput::make('price_to')->numeric(),
                    ]),
            ])
            ->searchable()
            ->defaultSort('code', 'desc');
    }

    protected function getSoldListingsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_sold_listings', function () {
            return app(GoyzerService::class)->getSoldListings();
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

        if (filled($filters['Category']['value'] ?? null)) {
            $collection = $collection->where('Category', $filters['Category']['value']);
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
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_sold_listings', function () {
            return app(GoyzerService::class)->getSoldListings();
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
}
