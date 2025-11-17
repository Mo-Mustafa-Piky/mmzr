<?php

namespace App\Filament\Resources\RequirementTypes\Pages;

use App\Filament\Resources\RequirementTypes\RequirementTypeResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;

class ListRequirementTypes extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = RequirementTypeResource::class;
    protected string $view = 'filament.resources.requirement-types.pages.list-requirement-types';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getRequirementTypesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('RequirementTypeID')
                    ->label('Requirement Type ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('RequirementType')
                    ->label('Requirement Type')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getRequirementTypesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getRequirementType();
        
        if (!$result || !isset($result['GetRequirementTypeData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $requirementTypes = is_array($result['GetRequirementTypeData']) && isset($result['GetRequirementTypeData'][0]) 
            ? $result['GetRequirementTypeData'] 
            : [$result['GetRequirementTypeData']];

        $collection = collect($requirementTypes)->mapWithKeys(function ($requirementType, $index) {
            return [$requirementType['RequirementTypeID'] ?? $index => $requirementType];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($requirementType) use ($search) {
                return str_contains(strtolower($requirementType['RequirementTypeID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($requirementType['RequirementType'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}