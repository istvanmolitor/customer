<?php

namespace Molitor\Customer\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Molitor\Customer\Repositories\CustomerRepository;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'customer');

        // Load API routes with /api prefix
        $this->app->make(Router::class)
            ->group(['prefix' => 'api'], __DIR__.'/../routes/api.php');
    }

    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }
}
