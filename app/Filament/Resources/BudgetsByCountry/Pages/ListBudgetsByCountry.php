<?php

namespace App\Filament\Resources\BudgetsByCountry\Pages;

use App\Filament\Resources\BudgetsByCountry\BudgetByCountryResource;
use App\Services\GoyzerService;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListBudgetsByCountry extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = BudgetByCountryResource::class;
    protected string $view = 'filament.resources.budgets-by-country.pages.list-budgets-by-country';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_budgets_by_country');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getBudgetsByCountryData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('BudgetID')
                    ->label('Budget ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UnitTypeID')
                    ->label('Unit Type ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UnitType')
                    ->label('Unit Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ClientRequirementTypeID')
                    ->label('Client Requirement Type ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('RequirementType')
                    ->label('Requirement Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CountryID')
                    ->label('Country ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Country')
                    ->label('Country')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('BudgetValue')
                    ->label('Budget Value')
                    ->sortable()
                    ->money('AED')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('Country')
                    ->form([
                        TextInput::make('country')
                            ->label('Country'),
                    ]),
                Filter::make('UnitType')
                    ->form([
                        TextInput::make('unit_type')
                            ->label('Unit Type'),
                    ]),
                Filter::make('RequirementType')
                    ->form([
                        TextInput::make('requirement_type')
                            ->label('Requirement Type'),
                    ]),
            ])
            ->searchable();
    }

    protected function getBudgetsByCountryData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_budgets_by_country', function () {
            return app(GoyzerService::class)->getBudgetByCountry();
        });
        
        if (!$result || !isset($result['GetBudgetByCountryData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $budgets = is_array($result['GetBudgetByCountryData']) && isset($result['GetBudgetByCountryData'][0]) 
            ? $result['GetBudgetByCountryData'] 
            : [$result['GetBudgetByCountryData']];

        $collection = collect($budgets)->mapWithKeys(function ($budget, $index) {
            return [$budget['BudgetID'] ?? $index => $budget];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($budget) use ($search) {
                return str_contains(strtolower($budget['BudgetID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['UnitType'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['RequirementType'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['Country'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($budget['BudgetValue'] ?? ''), strtolower($search));
            });
        }

        // Apply filters
        if (filled($filters['Country']['country'] ?? null)) {
            $collection = $collection->filter(function ($budget) use ($filters) {
                return str_contains(strtolower($budget['Country'] ?? ''), strtolower($filters['Country']['country']));
            });
        }

        if (filled($filters['UnitType']['unit_type'] ?? null)) {
            $collection = $collection->filter(function ($budget) use ($filters) {
                return str_contains(strtolower($budget['UnitType'] ?? ''), strtolower($filters['UnitType']['unit_type']));
            });
        }

        if (filled($filters['RequirementType']['requirement_type'] ?? null)) {
            $collection = $collection->filter(function ($budget) use ($filters) {
                return str_contains(strtolower($budget['RequirementType'] ?? ''), strtolower($filters['RequirementType']['requirement_type']));
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