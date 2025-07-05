<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Documents;

class DocumentsSeeder extends Seeder
{
    public function run(): void
    {
        Documents::factory()->count(25)->create();
    }
}
