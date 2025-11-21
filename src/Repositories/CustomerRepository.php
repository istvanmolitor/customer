<?php

declare(strict_types=1);

namespace Molitor\Customer\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Customer\Events\CustomerDestroyEvent;
use Molitor\Customer\Models\Customer;
use Molitor\Language\Repositories\LanguageRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    private Customer $customer;

    public function __construct(
        private AddressRepositoryInterface $addressRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private LanguageRepositoryInterface $languageRepository
    )
    {
        $this->customer = new Customer();
    }

    public function getByName(string $name): ?Customer
    {
        return $this->customer->where('name', $name)->first();
    }

    public function getByInternalName(string $internalName): ?Customer
    {
        return $this->customer->where('internal_name', $internalName)->first();
    }

    public function findOrCrate(string $internalName): Customer
    {
        $customer = $this->getByInternalName($internalName);
        if (!$customer) {

            return $this->customer->create([
                'name' => $internalName,
                'internal_name' => $internalName,
                'currency_id' => $this->currencyRepository->getDefaultId(),
                'language_id' => $this->languageRepository->getDefaultId(),
                'invoice_address_id' => $this->addressRepository->createEmptyId(),
                'shipping_address_id' => $this->addressRepository->createEmptyId(),
            ]);
        }
        return $customer;
    }

    public function getOptions(): array
    {
        return $this->customer->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function delete(Customer $customer): void
    {
        event(new CustomerDestroyEvent($customer));

        $invoiceAddress = $customer->invoiceAddress;
        $shippingAddress = $customer->shippingAddress;

        $customer->delete();
        $invoiceAddress->delete();
        $shippingAddress->delete();
    }

    public function getAll(): Collection
    {
        return $this->customer->orderBy('name')->get();
    }

    public function getById(int $customerId): Customer|null
    {
        return $this->customer->find($customerId);
    }

    public function getSellerOptions(): array
    {
        return $this->customer->orderBy('name')->where('is_seller', true)->pluck('name', 'id')->toArray();
    }
}
