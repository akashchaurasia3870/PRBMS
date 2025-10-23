<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class LeaveFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1;
        $createdBy = User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1;
        
        // Generate realistic date ranges
        $startDate = $this->faker->dateTimeBetween('-2 months', '+1 month');
        $daysCount = $this->faker->numberBetween(1, 10);
        $endDate = (clone $startDate)->modify("+{$daysCount} days");
        
        $status = $this->faker->randomElement(['pending', 'approved', 'rejected']);
        $leaveType = $this->faker->randomElement([
            'sick', 'casual', 'earned', 'unpaid'
        ]);
        
        // Generate realistic reasons based on leave type
        $reasons = [
            'sick' => ['Medical appointment', 'Flu symptoms', 'Doctor visit', 'Health checkup'],
            'casual' => ['Personal matters', 'Family event', 'Rest day', 'Personal time'],
            'earned' => ['Vacation time', 'Earned break', 'Annual leave', 'Time off'],
            'unpaid' => ['Extended leave', 'Personal reasons', 'Unpaid time', 'Special circumstances']
        ];
        
        $reason = $this->faker->randomElement($reasons[$leaveType]);
        
        $data = [
            'user_id' => $userId,
            'leave_type' => $leaveType,
            'status' => $status,
            'reason' => $reason,
            'description' => $this->faker->sentence(10),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_count' => $daysCount + 1,
            'created_by' => $createdBy,
            'deleted' => 0,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
        
        // Add approval/rejection details based on status
        if ($status === 'approved') {
            $data['approved_by'] = User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1;
        } elseif ($status === 'rejected') {
            $data['rejected_by'] = User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1;
            $data['rejection_reason'] = $this->faker->randomElement([
                'Insufficient notice period',
                'Conflicting project deadlines',
                'Team coverage unavailable',
                'Peak business period',
                'Documentation incomplete',
                'Previous leave quota exceeded'
            ]);
        }
        
        return $data;
    }
    
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'approved_by' => null,
                'rejected_by' => null,
                'rejection_reason' => null,
            ];
        });
    }
    
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'approved_by' => User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1,
                'rejected_by' => null,
                'rejection_reason' => null,
            ];
        });
    }
    
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'approved_by' => null,
                'rejected_by' => User::where('deleted', 0)->inRandomOrder()->first()?->id ?? 1,
                'rejection_reason' => $this->faker->randomElement([
                    'Insufficient notice period',
                    'Conflicting project deadlines',
                    'Team coverage unavailable',
                    'Peak business period'
                ]),
            ];
        });
    }
}
