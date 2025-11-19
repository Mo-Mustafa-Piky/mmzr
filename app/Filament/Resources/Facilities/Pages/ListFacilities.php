<?php

namespace App\Filament\Resources\Facilities\Pages;

use App\Filament\Resources\Facilities\FacilityResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListFacilities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = FacilityResource::class;
    protected string $view = 'filament.resources.facilities.pages.list-facilities';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_facilities');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getFacilitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('FacilityID')
                    ->label('Facility ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('FacilityName')
                    ->label('Facility Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getFacilitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_facilities', 3600, function () {
            return app(GoyzerService::class)->getFacility();
        });
        
        if (!$result || !isset($result['GetFacilityData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $facilities = is_array($result['GetFacilityData']) && isset($result['GetFacilityData'][0]) 
            ? $result['GetFacilityData'] 
            : [$result['GetFacilityData']];

        $collection = collect($facilities)->mapWithKeys(function ($facility, $index) {
            return [$facility['FacilityID'] ?? $index => $facility];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($facility) use ($search) {
                return str_contains(strtolower($facility['FacilityID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($facility['FacilityName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}