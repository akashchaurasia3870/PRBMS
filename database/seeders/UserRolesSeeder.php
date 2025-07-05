<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRoles;

class UserRolesSeeder extends Seeder
{
    public function run(): void
    {
        UserRoles::factory()->count(20)->create();
    }
}
