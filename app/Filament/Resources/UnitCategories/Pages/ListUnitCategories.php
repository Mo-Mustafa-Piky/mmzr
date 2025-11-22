<?php

namespace App\Filament\Resources\UnitCategories\Pages;

use App\Filament\Resources\UnitCategories\UnitCategoryResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListUnitCategories extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = UnitCategoryResource::class;
    protected string $view = 'filament.resources.unit-categories.pages.list-unit-categories';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_unit_categories');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getUnitCategoriesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('CategoryID')
                    ->label('Category ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CategoryName')
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getUnitCategoriesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_unit_categories', function () {
            return app(GoyzerService::class)->getUnitCategory();
        });
        
        if (!$result || !isset($result['GetUnitCategoryData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $unitCategories = is_array($result['GetUnitCategoryData']) && isset($result['GetUnitCategoryData'][0]) 
            ? $result['GetUnitCategoryData'] 
            : [$result['GetUnitCategoryData']];

        $collection = collect($unitCategories)->mapWithKeys(function ($unitCategory, $index) {
            return [$unitCategory['CategoryID'] ?? $index => $unitCategory];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($unitCategory) use ($search) {
                return str_contains(strtolower($unitCategory['CategoryID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($unitCategory['CategoryName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}