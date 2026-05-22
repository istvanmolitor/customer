<?php

namespace Molitor\Customer\database\seeders;

use Illuminate\Database\Seeder;
use Molitor\Address\Repositories\AddressRepositoryInterface;
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
            /** @var AddressRepositoryInterface $addressRepository */
            $addressRepository = app(AddressRepositoryInterface::class);

            CustomerGroup::factory(3)->create();

            for ($i = 0; $i < 10; $i++) {
                Customer::factory()->create([
                    'invoice_address_id' => $addressRepository->createEmptyId(),
                    'shipping_address_id' => $addressRepository->createEmptyId(),
                ]);
            }
        }
    }
}
