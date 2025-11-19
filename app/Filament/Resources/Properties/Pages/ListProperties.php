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
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_properties');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getPropertiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('code')->label('Code')->sortable()->searchable()->toggleable(),
                TextColumn::make('PropertyName')->label('Property Name')->sortable()->searchable()->toggleable(),
                TextColumn::make('RefNo')->label('Ref No')->sortable()->searchable()->toggleable(),
                TextColumn::make('Community')->label('Community')->sortable()->searchable()->toggleable(),
                TextColumn::make('Country')->label('Country')->sortable()->searchable()->toggleable(),
                TextColumn::make('State')->label('State')->sortable()->searchable()->toggleable(),
                TextColumn::make('CityName')->label('City')->sortable()->searchable()->toggleable(),
                TextColumn::make('Status')->label('Status')->sortable()->searchable()->toggleable(),
                TextColumn::make('Category')->label('Category')->sortable()->searchable()->toggleable(),
                TextColumn::make('Agent')->label('Agent')->sortable()->searchable()->toggleable(),
                TextColumn::make('ContactNumber')->label('Contact')->sortable()->toggleable(),
                TextColumn::make('BuiltUpArea')->label('Built Up Area')->sortable()->toggleable(),
                TextColumn::make('BedroomRange')->label('Bedrooms')->sortable()->toggleable(),
                TextColumn::make('HandoverDate')->label('Handover Date')->sortable()->toggleable(),
                TextColumn::make('CompletionDate')->label('Completion Date')->sortable()->toggleable(),
                TextColumn::make('LowestPriceUnit')->label('Lowest Price')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('Status')->options(['Available' => 'Available', 'Sold' => 'Sold', 'Reserved' => 'Reserved']),
                SelectFilter::make('Category')->options(['Residential' => 'Residential', 'Commercial' => 'Commercial']),
            ])
            ->searchable()
            ->defaultSort('code', 'desc');
    }

    protected function getPropertiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_properties', 3600, function () {
            return app(GoyzerService::class)->getProperties();
        });
        
        if (!$result || !isset($result['GetPropertiesData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $properties = is_array($result['GetPropertiesData']) && isset($result['GetPropertiesData'][0]) 
            ? $result['GetPropertiesData'] 
            : [$result['GetPropertiesData']];

        $collection = collect($properties)->mapWithKeys(function ($property, $index) {
            return [$property['code'] ?? $index => $property];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($property) use ($search) {
                return str_contains(strtolower($property['PropertyName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($property['RefNo'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($property['code'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['Status']['value'] ?? null)) {
            $collection = $collection->where('Status', $filters['Status']['value']);
        }

        if (filled($filters['Category']['value'] ?? null)) {
            $collection = $collection->where('Category', $filters['Category']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}
