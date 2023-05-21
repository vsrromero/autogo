<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('en_GB');
        $address_name = $faker->streetName();
        $address_number = $faker->buildingNumber();

        return [
            'name' => $faker->name(), 
            'address' => $address_number . ' ' . $address_name, 
            'postcode' => $faker->postcode(), 
            'city' => $faker->city(), 
            'county' => $faker->county(), 
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email(), 
        ];
    }
}
