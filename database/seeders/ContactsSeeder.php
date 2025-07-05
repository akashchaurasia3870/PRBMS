<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contacts;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        Contacts::factory()->count(25)->create();
    }
}
