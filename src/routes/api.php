<?php

use Illuminate\Support\Facades\Route;
use Molitor\Customer\Http\Controllers\Api\CustomerApiController;
use Molitor\Customer\Http\Controllers\Api\CustomerGroupApiController;

// Admin routes
Route::prefix('admin/customer')
    ->middleware(['api', 'auth:sanctum'])
    ->name('customer.')
    ->group(function () {
        // Customers
        Route::resource('customers', CustomerApiController::class);

        // Customer Groups
        Route::resource('customer-groups', CustomerGroupApiController::class);
    });
