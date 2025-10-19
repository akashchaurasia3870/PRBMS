<?php

namespace Database\Factories;

use App\Models\ExpenseType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseTypeFactory extends Factory
{
    protected $model = ExpenseType::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->randomElement([
                'Travel & Transportation',
                'Food & Dining',
                'Office Supplies',
                'Utilities',
                'Marketing & Advertising',
                'Training & Development',
                'Equipment & Hardware',
                'Maintenance & Repairs',
                'Insurance',
                'Software & Subscriptions',
                'Rent & Facilities',
                'Communication & Internet',
                'Professional Services',
                'Daily Basis Expenses',
                'Work Related Expenses'
            ]),
            'description' => $this->faker->paragraph(3),
            'created_by' => User::factory(),
            'updated_by' => null,
            'deleted_by' => null,
            'deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted' => 1,
            'deleted_by' => User::factory(),
            'deleted_at' => now(),
        ]);
    }
}