<?php

namespace Database\Factories;

use App\Models\ExpenseTracker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseTrackerFactory extends Factory
{
    protected $model = ExpenseTracker::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement([
                'Travel & Transportation',
                'Food & Dining',
                'Office Supplies',
                'Utilities',
                'Marketing & Advertising',
                'Training & Development',
                'Equipment & Hardware',
                'Maintenance & Repairs',
                'Insurance',
                'Software & Subscriptions'
            ]),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(10),
            'expense_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
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