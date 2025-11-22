<?php

namespace App\Filament\Resources\Titles\Pages;

use App\Filament\Resources\Titles\TitleResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListTitles extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = TitleResource::class;
    protected string $view = 'filament.resources.titles.pages.list-titles';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_titles');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getTitlesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('TitleID')
                    ->label('Title ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('TitleName')
                    ->label('Title Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getTitlesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_titles', function () {
            return app(GoyzerService::class)->getTitle();
        });
        
        if (!$result || !isset($result['GetTitleData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $titles = is_array($result['GetTitleData']) && isset($result['GetTitleData'][0]) 
            ? $result['GetTitleData'] 
            : [$result['GetTitleData']];

        $collection = collect($titles)->mapWithKeys(function ($title, $index) {
            return [$title['TitleID'] ?? $index => $title];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($title) use ($search) {
                return str_contains(strtolower($title['TitleID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($title['TitleName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}