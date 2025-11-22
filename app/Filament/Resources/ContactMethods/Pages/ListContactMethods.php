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
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListContactMethods extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ContactMethodResource::class;
    protected string $view = 'filament.resources.contact-methods.pages.list-contact-methods';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Cache::forget('goyzer_contact_methods');
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
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_contact_methods', function () {
            return app(GoyzerService::class)->getContactMethods();
        });
        
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