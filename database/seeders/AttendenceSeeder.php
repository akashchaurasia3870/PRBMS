<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendence;
use Carbon\Carbon;

class AttendenceSeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::today();

        $users = User::all();

        foreach ($users as $user) {
            $date = $startDate->copy();
            while ($date->lte($endDate)) {
                Attendence::factory()->create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'status' => fake()->randomElement(['present', 'absent']),
                    'check_in_time' => $date->copy()->setTime(9, 0, 0),
                    'check_out_time' => $date->copy()->setTime(17, 0, 0),
                ]);
                $date->addDay();
            }
        }
    }
}