<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ContactsFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id ?? User::factory();
        return [
            'user_id' => $userId,
            'country' => 'India',
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'area' => $this->faker->word(),
            'locality' => $this->faker->streetName(),
            'landmark' => $this->faker->streetSuffix(),
            'street' => $this->faker->streetName(),
            'house_no' => $this->faker->buildingNumber(),
            'contact_no' => $this->faker->numerify('98########'),
            'emergency_contact_no' => $this->faker->numerify('99########'),
            'pincode' => $this->faker->postcode(),
            'deleted' => false,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
