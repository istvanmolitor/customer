<?php

namespace Molitor\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Molitor\Customer\Repositories\CustomerRepository;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'customer');
    }

    public function register()
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }
}
