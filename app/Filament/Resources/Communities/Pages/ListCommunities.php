<?php

namespace App\Filament\Resources\Communities\Pages;

use App\Filament\Resources\Communities\CommunityResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListCommunities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = CommunityResource::class;
    protected string $view = 'filament.resources.communities.pages.list-communities';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_communities');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getCommunitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('CommunityID')
                    ->label('Community ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CommunityName')
                    ->label('Community Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('DistrictName')
                    ->label('District')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getDistrictName($record['DistrictID'] ?? '');
                    }),
                TextColumn::make('HaveSubComm')
                    ->label('Has Sub-Communities')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'true' ? 'success' : 'gray'),
                TextColumn::make('CommunityDescription')
                    ->label('Description')
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('DistrictID')
                    ->label('District')
                    ->options($this->getDistrictOptions()),
            ])
            ->searchable();
    }

    protected function getCommunitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_communities', 3600, function () {
            return app(GoyzerService::class)->getCommunities();
        });
        
        if (!$result || !isset($result['GetCommunityData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $communities = is_array($result['GetCommunityData']) && isset($result['GetCommunityData'][0]) 
            ? $result['GetCommunityData'] 
            : [$result['GetCommunityData']];

        $collection = collect($communities)->mapWithKeys(function ($community, $index) {
            return [$community['CommunityID'] ?? $index => $community];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($community) use ($search) {
                return str_contains(strtolower($community['CommunityID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($community['CommunityName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($community['CommunityDescription'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['DistrictID']['value'] ?? null)) {
            $collection = $collection->where('DistrictID', $filters['DistrictID']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }

    protected function getDistrictName(string $districtId): string
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_districts', 3600, function () {
            return app(GoyzerService::class)->getDistricts();
        });
        
        if (!$result || !isset($result['GetDistrictData'])) {
            return $districtId;
        }

        $districts = is_array($result['GetDistrictData']) && isset($result['GetDistrictData'][0]) 
            ? $result['GetDistrictData'] 
            : [$result['GetDistrictData']];

        foreach ($districts as $district) {
            if (($district['DistrictID'] ?? '') === $districtId) {
                return $district['DistrictName'] ?? $districtId;
            }
        }

        return $districtId;
    }

    protected function getDistrictOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_districts', 3600, function () {
            return app(GoyzerService::class)->getDistricts();
        });
        
        if (!$result || !isset($result['GetDistrictData'])) {
            return [];
        }

        $districts = is_array($result['GetDistrictData']) && isset($result['GetDistrictData'][0]) 
            ? $result['GetDistrictData'] 
            : [$result['GetDistrictData']];

        $options = [];
        foreach ($districts as $district) {
            $options[$district['DistrictID'] ?? ''] = $district['DistrictName'] ?? '';
        }

        return $options;
    }
}