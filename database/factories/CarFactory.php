<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('en_GB');

        return [
            'version_id' => $faker->numberBetween(8, 12),
            'reg' => $faker->regexify('[A-Z]{2}[0-9]{2} [A-Z]{3}'),
            'available' => $faker->boolean,
            'ml' => $faker->numberBetween(0, 10000),            
        ];
    }
}
