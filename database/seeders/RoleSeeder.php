<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin', 'role_desc' => 'Administrator', 'role_lvl' => 3],
            ['role_name' => 'Manager', 'role_desc' => 'Manager', 'role_lvl' => 2],
            ['role_name' => 'Chairman', 'role_desc' => 'Chairman', 'role_lvl' => 3],
            ['role_name' => 'Employee', 'role_desc' => 'Employee', 'role_lvl' => 1],
            ['role_name' => 'HR', 'role_desc' => 'Human Resources', 'role_lvl' => 2],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role['role_name']], $role);
        }
    }
}
