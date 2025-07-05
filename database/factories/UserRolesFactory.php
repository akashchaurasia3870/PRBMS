<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Role;

class UserRolesFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id ?? User::factory();
        $roleId = Role::inRandomOrder()->first()?->id ?? 1;
        return [
            'user_id' => $userId,
            'role_id' => $roleId,
            'role_lvl' => $this->faker->numberBetween(0, 3),
            'deleted' => false,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
