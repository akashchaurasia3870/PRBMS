<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        // Create a mix of leave requests with different statuses
        Leave::factory()->count(5)->pending()->create();
        Leave::factory()->count(8)->approved()->create();
        Leave::factory()->count(3)->rejected()->create();
        
        // Create some additional random leaves
        Leave::factory()->count(4)->create();
    }
}
