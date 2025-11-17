<?php

namespace App\Filament\Resources\UnitSubTypes\Pages;

use App\Filament\Resources\UnitSubTypes\UnitSubTypeResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUnitSubTypes extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = UnitSubTypeResource::class;
    protected string $view = 'filament.resources.unit-sub-types.pages.list-unit-sub-types';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getUnitSubTypesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('UnitSubTypeID')
                    ->label('Unit Sub Type ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UnitSubTypeName')
                    ->label('Unit Sub Type Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UnitTypeID')
                    ->label('Unit Type ID')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getUnitSubTypesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getUnitSubType();
        
        if (!$result || !isset($result['GetUnitSubTypeData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $unitSubTypes = is_array($result['GetUnitSubTypeData']) && isset($result['GetUnitSubTypeData'][0]) 
            ? $result['GetUnitSubTypeData'] 
            : [$result['GetUnitSubTypeData']];

        $collection = collect($unitSubTypes)->mapWithKeys(function ($unitSubType, $index) {
            return [$unitSubType['UnitSubTypeID'] ?? $index => $unitSubType];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($unitSubType) use ($search) {
                return str_contains(strtolower($unitSubType['UnitSubTypeID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($unitSubType['UnitSubTypeName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($unitSubType['UnitTypeID'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}