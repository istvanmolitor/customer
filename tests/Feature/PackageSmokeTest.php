<?php

namespace Molitor\Customer\Tests\Feature;

use Molitor\Customer\Providers\CustomerServiceProvider;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue(class_exists(CustomerServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(CustomerServiceProvider::class));
    }
}

