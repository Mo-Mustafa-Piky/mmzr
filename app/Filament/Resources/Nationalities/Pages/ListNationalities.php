<?php

namespace App\Filament\Resources\Nationalities\Pages;

use App\Filament\Resources\Nationalities\NationalityResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListNationalities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = NationalityResource::class;
    protected string $view = 'filament.resources.nationalities.pages.list-nationalities';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_nationalities');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getNationalitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('NationalityID')
                    ->label('Nationality ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('NationalityName')
                    ->label('Nationality Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getNationalitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_nationalities', 3600, function () {
            return app(GoyzerService::class)->getNationality();
        });
        
        if (!$result || !isset($result['GetNationalityData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $nationalities = is_array($result['GetNationalityData']) && isset($result['GetNationalityData'][0]) 
            ? $result['GetNationalityData'] 
            : [$result['GetNationalityData']];

        $collection = collect($nationalities)->mapWithKeys(function ($nationality, $index) {
            return [$nationality['NationalityID'] ?? $index => $nationality];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($nationality) use ($search) {
                return str_contains(strtolower($nationality['NationalityID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($nationality['NationalityName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}