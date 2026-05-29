<?php

declare(strict_types=1);

namespace Molitor\Customer\Repositories;

use Molitor\Customer\Models\CustomerGroup;

interface CustomerGroupRepositoryInterface
{
    public function create(string $name, ?string $description): CustomerGroup;
}

