<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertiesResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListProperties extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = PropertiesResource::class;
    protected string $view = 'filament.resources.properties.pages.list-properties';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')
                ->label('Sync from Goyzer')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Artisan::call('goyzer:sync-properties');
                    Notification::make()
                        ->title('Properties synced successfully')
                        ->success()
                        ->send();
                })
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getPropertiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('Images')
                    ->label('Images')
                    ->default('No images')
                    ->toggleable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('PropertyName')
                    ->label('Property Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('RefNo')
                    ->label('Ref No')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Community')
                    ->label('Community')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Country')
                    ->label('Country')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('State')
                    ->label('State')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('CityName')
                    ->label('City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Status')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Category')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Agent')
                    ->label('Agent')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('ContactNumber')
                    ->label('Contact')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('BuiltUpArea')
                    ->label('Built Up Area')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('NoOfFloors')
                    ->label('Floors')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('HandoverDate')
                    ->label('Handover Date')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('CompletionDate')
                    ->label('Completion Date')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('SubCommunity')
                    ->label('Sub Community')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Vicinity')
                    ->label('Vicinity')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('Appartments')
                    ->label('Apartments')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Villa')
                    ->label('Villas')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Shops')
                    ->label('Shops')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Offices')
                    ->label('Offices')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('LowestPriceUnit')
                    ->label('Lowest Price')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('LowestSizeUnit')
                    ->label('Lowest Size')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('CurrencyAbr')
                    ->label('Currency')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Measurement')
                    ->label('Measurement')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('BedroomRange')
                    ->label('Bedroom Range')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('GoogleCoordinates')
                    ->label('Coordinates')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('EscrowAccountNo')
                    ->label('Escrow Account')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Remarks')
                    ->label('Remarks')
                    ->sortable()
                    ->toggleable()
                    ->limit(50),
                TextColumn::make('UniqueSellingPoints')
                    ->label('USP')
                    ->sortable()
                    ->toggleable()
                    ->limit(50),
                TextColumn::make('CountryCode')
                    ->label('Country Code')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('PropertyOverView')
                    ->label('Overview')
                    ->sortable()
                    ->toggleable()
                    ->limit(50),
                TextColumn::make('DeveloperDesc')
                    ->label('Developer')
                    ->sortable()
                    ->toggleable()
                    ->limit(50),
                TextColumn::make('PropertyOwnerShipDesc')
                    ->label('Ownership')
                    ->sortable()
                    ->toggleable()
                    ->limit(50),
                TextColumn::make('CountryID')
                    ->label('Country ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('StateID')
                    ->label('State ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('CityID')
                    ->label('City ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('DistrictID')
                    ->label('District ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('CommunityID')
                    ->label('Community ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('SubCommunityID')
                    ->label('Sub Community ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('AgentID')
                    ->label('Agent ID')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('Community')
                    ->options($this->getCommunityOptions())
                    ->searchable(),
                SelectFilter::make('Country')
                    ->options($this->getCountryOptions())
                    ->searchable(),
                SelectFilter::make('State')
                    ->options($this->getStateOptions())
                    ->searchable(),
                SelectFilter::make('CityName')
                    ->label('City')
                    ->options($this->getCityOptions())
                    ->searchable(),
                SelectFilter::make('Status')
                    ->options($this->getStatusOptions())
                    ->searchable(),
                SelectFilter::make('Category')
                    ->options($this->getCategoryOptions())
                    ->searchable(),
                SelectFilter::make('Agent')
                    ->options($this->getAgentOptions())
                    ->searchable(),
                Filter::make('CompletionDate')
                    ->form([
                        DatePicker::make('completion_from')
                            ->label('Completion Date From'),
                        DatePicker::make('completion_to')
                            ->label('Completion Date To'),
                    ])
                    ->query(function ($query, array $data) {
                        // This will be handled in getPropertiesData method
                        return $query;
                    }),
            ])
            ->searchable();
    }

    protected function getPropertiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $query = \Illuminate\Support\Facades\DB::table('goyzer_properties');
        
        $allData = $query->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        
        if (empty($allData)) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $collection = collect($allData)->mapWithKeys(function ($property, $index) {
            return [$property['code'] ?? $index => $property];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($property) use ($search) {
                $searchFields = ['code', 'PropertyName', 'Community', 'Country', 'RefNo', 'Agent', 'CityName'];
                
                foreach ($searchFields as $field) {
                    $value = $property[$field] ?? '';
                    if (is_string($value) && str_contains(strtolower($value), strtolower($search))) {
                        return true;
                    }
                }
                
                return false;
            });
        }

        // Apply filters
        if (!empty($filters)) {
            foreach ($filters as $filterName => $filterValue) {
                if (filled($filterValue)) {
                    if ($filterName === 'CompletionDate' && is_array($filterValue)) {
                        $collection = $this->applyDateFilter($collection, $filterValue);
                    } elseif (is_string($filterValue)) {
                        $collection = $collection->filter(function ($property) use ($filterName, $filterValue) {
                            $propertyValue = $property[$filterName] ?? '';
                            return is_string($propertyValue) && str_contains(strtolower($propertyValue), strtolower($filterValue));
                        });
                    }
                }
            }
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }

    protected function getCommunityOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')
            ->get()
            ->map(fn($item) => json_decode($item->data, true))
            ->toArray();

        return collect($properties)
            ->pluck('Community')
            ->filter(fn($item) => is_string($item) && !empty($item))
            ->unique()
            ->sort()
            ->mapWithKeys(fn($item) => [$item => $item])
            ->toArray();
    }

    protected function getCountryOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('Country')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function getStateOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('State')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function getCityOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('CityName')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function getStatusOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('Status')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function getCategoryOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('Category')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function getAgentOptions(): array
    {
        $properties = \Illuminate\Support\Facades\DB::table('goyzer_properties')->get()->map(fn($item) => json_decode($item->data, true))->toArray();
        return collect($properties)->pluck('Agent')->filter(fn($item) => is_string($item) && !empty($item))->unique()->sort()->mapWithKeys(fn($item) => [$item => $item])->toArray();
    }

    protected function applyDateFilter($collection, array $dateFilter)
    {
        if (isset($dateFilter['completion_from']) || isset($dateFilter['completion_to'])) {
            $collection = $collection->filter(function ($property) use ($dateFilter) {
                $completionDate = $property['CompletionDate'] ?? null;
                
                if (!$completionDate) {
                    return false;
                }

                try {
                    $propertyDate = Carbon::parse($completionDate);
                    
                    if (isset($dateFilter['completion_from'])) {
                        $fromDate = Carbon::parse($dateFilter['completion_from']);
                        if ($propertyDate->lt($fromDate)) {
                            return false;
                        }
                    }
                    
                    if (isset($dateFilter['completion_to'])) {
                        $toDate = Carbon::parse($dateFilter['completion_to']);
                        if ($propertyDate->gt($toDate)) {
                            return false;
                        }
                    }
                    
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            });
        }
        
        return $collection;
    }
}