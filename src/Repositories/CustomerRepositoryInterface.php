<?php

declare(strict_types=1);

namespace Molitor\Customer\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Customer\Models\Customer;
use Molitor\User\Models\User;

interface CustomerRepositoryInterface
{
    public function getByName(string $name): ?Customer;

    public function getByInternalName(string $internalName): ?Customer;

    public function findOrCrate(string $internalName): Customer;

    public function getOptions(): array;

    public function delete(Customer $customer): void;

    public function getAll(): Collection;

    public function getById(int $customerId): ?Customer;

    public function getSellerOptions(): array;

    public function getByUser(User $user): Customer;
}
