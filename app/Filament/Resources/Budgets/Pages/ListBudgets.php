<?php

namespace App\Filament\Resources\Budgets\Pages;

use App\Filament\Resources\Budgets\BudgetResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListBudgets extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = BudgetResource::class;
    protected string $view = 'filament.resources.budgets.pages.list-budgets';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_budgets');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getBudgetsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('BudgetID')
                    ->label('Budget ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UnitID')
                    ->label('Unit ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ClientRequirementTypeID')
                    ->label('Client Requirement Type ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('BudgetValue')
                    ->label('Budget Value')
                    ->sortable()
                    ->money('AED')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('UnitID')
                    ->form([
                        TextInput::make('unit_id')
                            ->label('Unit ID'),
                    ]),
                Filter::make('ClientRequirementTypeID')
                    ->form([
                        TextInput::make('client_requirement_type_id')
                            ->label('Client Requirement Type ID'),
                    ]),
            ])
            ->searchable();
    }

    protected function getBudgetsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_budgets', function () {
            return app(GoyzerService::class)->getBudget();
        });
        
        if (!$result || !isset($result['GetBudgetData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $budgets = is_array($result['GetBudgetData']) && isset($result['GetBudgetData'][0]) 
            ? $result['GetBudgetData'] 
            : [$result['GetBudgetData']];

        $collection = collect($budgets)->mapWithKeys(function ($budget, $index) {
            return [$budget['BudgetID'] ?? $index => $budget];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($budget) use ($search) {
                return str_contains(strtolower($budget['BudgetID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['UnitID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['ClientRequirementTypeID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['BudgetValue'] ?? ''), strtolower($search));
            });
        }

        // Apply filters
        if (filled($filters['UnitID']['unit_id'] ?? null)) {
            $collection = $collection->filter(function ($budget) use ($filters) {
                return str_contains(strtolower($budget['UnitID'] ?? ''), strtolower($filters['UnitID']['unit_id']));
            });
        }

        if (filled($filters['ClientRequirementTypeID']['client_requirement_type_id'] ?? null)) {
            $collection = $collection->filter(function ($budget) use ($filters) {
                return str_contains(strtolower($budget['ClientRequirementTypeID'] ?? ''), strtolower($filters['ClientRequirementTypeID']['client_requirement_type_id']));
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