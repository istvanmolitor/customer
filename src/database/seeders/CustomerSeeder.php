<?php

namespace Molitor\Customer\database\seeders;

use Illuminate\Database\Seeder;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Models\CustomerGroup;
use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Services\AclManagementService;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            /** @var AclManagementService $aclService */
            $aclService = app(AclManagementService::class);
            $aclService->createPermission('customer', 'Ügyfelek kezelése', 'admin');
        } catch (PermissionException $e) {
            $this->command->error($e->getMessage());
        }

        if (app()->isLocal()) {
            CustomerGroup::factory(3)->create();
            Customer::factory(10)->create();
        }
    }
}
