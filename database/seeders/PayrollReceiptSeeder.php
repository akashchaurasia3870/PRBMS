<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollReceipt;

class PayrollReceiptSeeder extends Seeder
{
    public function run(): void
    {
        // Seed 50 sample payroll receipts
        PayrollReceipt::factory()->count(15)->create();
    }
}
