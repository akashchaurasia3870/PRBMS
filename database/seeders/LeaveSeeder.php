<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        Leave::factory()->count(25)->create();
    }
}
