<?php

namespace App\Filament\Resources\Bedrooms\Pages;

use App\Filament\Resources\Bedrooms\BedroomResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListBedrooms extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = BedroomResource::class;
    protected string $view = 'filament.resources.bedrooms.pages.list-bedrooms';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getBedroomsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('BedroomID')
                    ->label('Bedroom ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Bedroom')
                    ->label('Bedroom')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getBedroomsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getBedrooms();
        
        if (!$result || !isset($result['GetBedroomsData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $bedrooms = is_array($result['GetBedroomsData']) && isset($result['GetBedroomsData'][0]) 
            ? $result['GetBedroomsData'] 
            : [$result['GetBedroomsData']];

        $collection = collect($bedrooms)->mapWithKeys(function ($bedroom, $index) {
            return [$bedroom['BedroomID'] ?? $index => $bedroom];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($bedroom) use ($search) {
                return str_contains(strtolower($bedroom['Bedroom'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($bedroom['BedroomID'] ?? ''), strtolower($search));
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