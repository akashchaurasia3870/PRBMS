<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalaryStructure;

class SalaryStructureSeeder extends Seeder
{
    public function run(): void
    {
        // generate 50 sample salary structures
        SalaryStructure::factory()->count(26)->create();
    }
}
