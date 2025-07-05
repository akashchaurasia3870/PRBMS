<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class LeaveFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id ?? User::factory();
        $start = $this->faker->dateTimeBetween('-1 month', 'now');
        $end = (clone $start)->modify('+'.rand(0,5).' days');
        return [
            'user_id' => $userId,
            'leave_type' => $this->faker->randomElement(['sick','casual','earned','unpaid']),
            'status' => $this->faker->randomElement(['pending','approved','rejected']),
            'reason' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'start_date' => $start,
            'end_date' => $end,
            'deleted' => false,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => $start,
            'updated_at' => $end,
        ];
    }
}
