<?php

namespace App\Filament\Resources\Amenities\Pages;

use App\Filament\Resources\Amenities\AmenityResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListAmenities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = AmenityResource::class;
    protected string $view = 'filament.resources.amenities.pages.list-amenities';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_amenities');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getAmenitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('AmenityID')
                    ->label('Amenity ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('AmenityName')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getAmenitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_amenities', function () {
            return app(GoyzerService::class)->getAmenities();
        });
        
        if (!$result || !isset($result['Amenity'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $amenities = is_array($result['Amenity']) && isset($result['Amenity'][0]) 
            ? $result['Amenity'] 
            : [$result['Amenity']];

        $collection = collect($amenities)->mapWithKeys(function ($amenity, $index) {
            return [$amenity['AmenityID'] ?? $index => $amenity];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($amenity) use ($search) {
                return str_contains(strtolower($amenity['AmenityName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($amenity['AmenityID'] ?? ''), strtolower($search));
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