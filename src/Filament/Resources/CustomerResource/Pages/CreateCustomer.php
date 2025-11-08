<?php

namespace Molitor\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Molitor\Customer\Filament\Resources\CustomerResource;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Models\Address;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    public function getBreadcrumb(): string
    {
        return __('customer::common.create');
    }

    public function getTitle(): string
    {
        return __('customer::customer.create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['invoice_address']) && is_array($data['invoice_address'])) {
            /** @var AddressRepositoryInterface $addressRepository */
            $addressRepository = app(AddressRepositoryInterface::class);
            $address = new Address();
            $addressRepository->saveAddress($address, $data['invoice_address']);
            $data['invoice_address_id'] = $address->id;
            unset($data['invoice_address']);
        }

        return $data;
    }
}
