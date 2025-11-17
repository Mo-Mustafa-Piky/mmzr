<?php

namespace App\Filament\Resources\ContactMethods\Pages;

use App\Filament\Resources\ContactMethods\ContactMethodResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;

class ListContactMethods extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ContactMethodResource::class;
    protected string $view = 'filament.resources.contact-methods.pages.list-contact-methods';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (?string $search = null, ?array $filters = null, ?int $page = null, ?int $recordsPerPage = null): LengthAwarePaginator => $this->getContactMethodsData($search, $filters ?? [], $page ?? 1, $recordsPerPage ?? 10))
            ->columns([
                TextColumn::make('ContactMethodID')
                    ->label('Contact Method ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ContactMethodName')
                    ->label('Contact Method Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->searchable();
    }

    protected function getContactMethodsData(?string $search = null, array $filters = [], int $page = 1, int $recordsPerPage = 10): LengthAwarePaginator
    {
        $goyzerService = app(GoyzerService::class);
        $result = $goyzerService->getContactMethods();
        
        if (!$result || !isset($result['GetContactMethodData'])) {
            return new LengthAwarePaginator(collect(), 0, $recordsPerPage, $page);
        }

        $contactMethods = is_array($result['GetContactMethodData']) && isset($result['GetContactMethodData'][0]) 
            ? $result['GetContactMethodData'] 
            : [$result['GetContactMethodData']];

        $collection = collect($contactMethods)->mapWithKeys(function ($contactMethod, $index) {
            return [$contactMethod['ContactMethodID'] ?? $index => $contactMethod];
        });

        if (filled($search)) {
            $collection = $collection->filter(function ($contactMethod) use ($search) {
                return str_contains(strtolower($contactMethod['ContactMethodID'] ?? ''), strtolower($search)) ||
                       str_contains(strtolower($contactMethod['ContactMethodName'] ?? ''), strtolower($search));
            });
        }

        $total = $collection->count();
        $paginatedData = $collection->forPage($page, $recordsPerPage);

        return new LengthAwarePaginator($paginatedData, $total, $recordsPerPage, $page);
    }
}