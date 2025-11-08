<?php

namespace Molitor\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Molitor\Customer\Filament\Resources\CustomerResource;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    public function getBreadcrumb(): string
    {
        return __('customer::common.list');
    }

    public function getTitle(): string
    {
        return __('customer::customer.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('customer::customer.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
