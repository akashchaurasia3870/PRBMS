<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id ?? User::factory();
        $date = $this->faker->dateTimeBetween('-2 months', 'now');
        return [
            'user_id' => $userId,
            'status' => $this->faker->randomElement(['present','absent','late','on_leave','work_from_home']),
            'check_in_time' => $date,
            'check_out_time' => $date,
            'date' => $date,
            'deleted' => false,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
