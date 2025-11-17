<?php

namespace App\Filament\Resources\SubCommunities\Pages;

use App\Filament\Resources\SubCommunities\SubCommunityResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSubCommunities extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = SubCommunityResource::class;
    protected string $view = 'filament.resources.sub-communities.pages.list-sub-communities';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getSubCommunitiesData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('SubCommunityID')
                    ->label('Sub-Community ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('SubCommunityName')
                    ->label('Sub-Community Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('CommunityName')
                    ->label('Community')
                    ->sortable()
                    ->searchable()
                    ->state(function (array $record): string {
                        return $this->getCommunityName($record['CommunityID'] ?? '');
                    }),
            ])
            ->filters([
                SelectFilter::make('CommunityID')
                    ->label('Community')
                    ->options($this->getCommunityOptions()),
            ])
            ->searchable();
    }

    protected function getSubCommunitiesData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getSubCommunity();
        
        if (!$result || !isset($result['GetSubCommunityData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $subCommunities = is_array($result['GetSubCommunityData']) && isset($result['GetSubCommunityData'][0]) 
            ? $result['GetSubCommunityData'] 
            : [$result['GetSubCommunityData']];

        $collection = collect($subCommunities)->mapWithKeys(function ($subCommunity, $index) {
            return [$subCommunity['SubCommunityID'] ?? $index => $subCommunity];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($subCommunity) use ($search) {
                return str_contains(strtolower($subCommunity['SubCommunityID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($subCommunity['SubCommunityName'] ?? ''), strtolower($search));
            });
        }

        if (filled($filters['CommunityID']['value'] ?? null)) {
            $collection = $collection->where('CommunityID', $filters['CommunityID']['value']);
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }

    protected function getCommunityName(string $communityId): string
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getCommunities();
        
        if (!$result || !isset($result['GetCommunityData'])) {
            return $communityId;
        }

        $communities = is_array($result['GetCommunityData']) && isset($result['GetCommunityData'][0]) 
            ? $result['GetCommunityData'] 
            : [$result['GetCommunityData']];

        foreach ($communities as $community) {
            if (($community['CommunityID'] ?? '') === $communityId) {
                return $community['CommunityName'] ?? $communityId;
            }
        }

        return $communityId;
    }

    protected function getCommunityOptions(): array
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getCommunities();
        
        if (!$result || !isset($result['GetCommunityData'])) {
            return [];
        }

        $communities = is_array($result['GetCommunityData']) && isset($result['GetCommunityData'][0]) 
            ? $result['GetCommunityData'] 
            : [$result['GetCommunityData']];

        $options = [];
        foreach ($communities as $community) {
            $options[$community['CommunityID'] ?? ''] = $community['CommunityName'] ?? '';
        }

        return $options;
    }
}