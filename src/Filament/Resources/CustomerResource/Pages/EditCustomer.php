<?php

namespace Molitor\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Molitor\Customer\Filament\Resources\CustomerResource;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Models\Address;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    public function getBreadcrumb(): string
    {
        return __('customer::common.edit');
    }

    public function getTitle(): string
    {
        return __('customer::customer.edit');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        if (method_exists($record, 'invoiceAddress') && $record->invoiceAddress) {
            $address = $record->invoiceAddress;
            $data['invoice_address'] = [
                'name' => $address->name,
                'country_id' => $address->country_id,
                'zip_code' => $address->zip_code,
                'city' => $address->city,
                'address' => $address->address,
            ];
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var AddressRepositoryInterface $addressRepository */
        $addressRepository = app(AddressRepositoryInterface::class);

        $record = $this->getRecord();
        $address = $record->invoiceAddress ?: new Address();

        if (isset($data['invoice_address']) && is_array($data['invoice_address'])) {
            $addressRepository->saveAddress($address, $data['invoice_address']);
            $data['invoice_address_id'] = $address->id;
            unset($data['invoice_address']);
        }

        return $data;
    }
}
