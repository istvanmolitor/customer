<?php

declare(strict_types=1);

namespace Molitor\Customer\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Molitor\Currency\Models\Currency;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Models\CustomerGroup;
use Molitor\Language\Models\Language;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'internal_name' => $this->faker->unique()->words(2, true),
            'is_seller' => $this->faker->boolean(),
            'is_buyer' => $this->faker->boolean(),
            'user_id' => null,
            'description' => $this->faker->sentence(),
            'customer_group_id' => CustomerGroup::inRandomOrder()->value('id'),
            'currency_id' => Currency::whereIn('code', ['HUF', 'USD', 'EUR'])->inRandomOrder()->value('id'),
            'language_id' => Language::whereIn('code', ['hu', 'en', 'de'])->inRandomOrder()->value('id'),
        ];
    }
}

