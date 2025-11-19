<?php

namespace App\Filament\Resources\Agents\Pages;

use App\Filament\Resources\Agents\AgentResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListAgents extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = AgentResource::class;
    protected string $view = 'filament.resources.agents.pages.list-agents';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_agents');
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
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getAgentsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('AgentID')
                    ->label('Agent ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('AgentName')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('AgentEmail')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('AgentPhone')
                    ->label('Phone'),
                TextColumn::make('AgentMobile')
                    ->label('Mobile'),
                TextColumn::make('AgentDesignation')
                    ->label('Designation')
                    ->sortable(),
                TextColumn::make('AgentStatus')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('AgentStatus')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->searchable();
    }

    protected function getAgentsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_agents', 3600, function () {
            return app(GoyzerService::class)->getAgents();
        });
        
        if (!$result || !isset($result['GetAgentData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $agents = is_array($result['GetAgentData']) && isset($result['GetAgentData'][0]) 
            ? $result['GetAgentData'] 
            : [$result['GetAgentData']];

        $collection = collect($agents)->mapWithKeys(function ($agent, $index) {
            return [$agent['AgentID'] ?? $index => $agent];
        });

        // Apply search
        if (filled($search)) {
            $collection = $collection->filter(function ($agent) use ($search) {
                return str_contains(strtolower($agent['AgentName'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($agent['AgentEmail'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($agent['AgentID'] ?? ''), strtolower($search));
            });
        }

        // Apply filters
        if (filled($filters['AgentStatus']['value'] ?? null)) {
            $collection = $collection->where('AgentStatus', $filters['AgentStatus']['value']);
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