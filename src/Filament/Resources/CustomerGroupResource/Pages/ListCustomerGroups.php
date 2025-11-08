<?php

namespace Molitor\Customer\Filament\Resources\CustomerGroupResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Molitor\Customer\Filament\Resources\CustomerGroupResource;

class ListCustomerGroups extends ListRecords
{
    protected static string $resource = CustomerGroupResource::class;

    public function getBreadcrumb(): string
    {
        return __('customer::common.list');
    }

    public function getTitle(): string
    {
        return __('customer::customer_group.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('customer::customer_group.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
