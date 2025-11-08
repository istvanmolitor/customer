<?php

declare(strict_types=1);

namespace Molitor\Customer\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Molitor\Customer\Models\CustomerGroup;

class CustomerGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
        ];
    }
}

