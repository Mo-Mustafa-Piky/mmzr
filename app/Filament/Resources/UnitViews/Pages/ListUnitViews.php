<?php

namespace App\Filament\Resources\UnitViews\Pages;

use App\Filament\Resources\UnitViews\UnitViewResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUnitViews extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = UnitViewResource::class;
    protected string $view = 'filament.resources.unit-views.pages.list-unit-views';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getUnitViewsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('ViewID')
                    ->label('View ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ViewName')
                    ->label('View Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getUnitViewsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getUnitView();
        
        if (!$result || !isset($result['GetUnitViewData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $unitViews = is_array($result['GetUnitViewData']) && isset($result['GetUnitViewData'][0]) 
            ? $result['GetUnitViewData'] 
            : [$result['GetUnitViewData']];

        $collection = collect($unitViews)->mapWithKeys(function ($unitView, $index) {
            return [$unitView['ViewID'] ?? $index => $unitView];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($unitView) use ($search) {
                return str_contains(strtolower($unitView['ViewID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($unitView['ViewName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}