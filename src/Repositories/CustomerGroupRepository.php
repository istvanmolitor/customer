<?php

declare(strict_types=1);

namespace Molitor\Customer\Repositories;

use Molitor\Customer\Models\CustomerGroup;

class CustomerGroupRepository implements CustomerGroupRepositoryInterface
{
    private CustomerGroup $customerGroup;

    public function __construct()
    {
        $this->customerGroup = new CustomerGroup;
    }

    public function create(string $name, ?string $description): CustomerGroup
    {
        return $this->customerGroup->create([
            'name' => $name,
            'description' => $description,
        ]);
    }
}

