<?php

namespace Database\Factories;

use App\Models\SalaryStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryStructureFactory extends Factory
{
    protected $model = SalaryStructure::class;

    public function definition(): array
    {
        return [
            'user_id'         => $this->faker->numberBetween(1, 26),
            'basic_salary'    => $this->faker->randomFloat(2, 10000, 50000),
            'hra'             => $this->faker->randomFloat(2, 2000, 8000),
            'da'              => $this->faker->randomFloat(2, 1500, 5000),
            'other_allowance' => $this->faker->randomFloat(2, 500, 3000),
            'created_by'      => $this->faker->numberBetween(1,26),
            'update_by'       => $this->faker->numberBetween(1.26),
            'deleted_by'      => null,
            'deleted_at'      => null,
            'deleted'         => false,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
