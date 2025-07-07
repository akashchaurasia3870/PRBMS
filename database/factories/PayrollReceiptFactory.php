<?php

namespace Database\Factories;

use App\Models\PayrollReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollReceiptFactory extends Factory
{
    protected $model = PayrollReceipt::class;

    public function definition(): array
    {
        $totalSalary = $this->faker->randomFloat(2, 20000, 80000);
        $workingDays = $this->faker->numberBetween(25, 31);
        $presentDays = $this->faker->numberBetween(20, $workingDays);
        $leaveDays   = $workingDays - $presentDays;
        $netSalary   = ($totalSalary / $workingDays) * $presentDays;

        return [
            'user_id'              => $this->faker->numberBetween(1, 26),
            'month'                => $this->faker->numberBetween(1, 12),
            'year'                 => $this->faker->numberBetween(2023, 2025),
            'total_working_days'   => $workingDays,
            'present_days'         => $presentDays,
            'leave_days'           => $leaveDays,
            'total_salary'         => $totalSalary,
            'net_salary'           => round($netSalary, 2),
            'status'               => $this->faker->randomElement(['generated', 'paid']),
            'generated_at'         => now(),
            'paid_at'              => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'created_by'           => $this->faker->name(),
            'updated_by'           => $this->faker->name(),
            'deleted_by'           => null,
            'deleted'              => false,
            'deleted_at'           => null,
            'created_at'           => now(),
            'updated_at'           => now(),
        ];
    }
}
