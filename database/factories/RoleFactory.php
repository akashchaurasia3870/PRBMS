<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        $roles = [
            ['role_name' => 'Admin', 'role_desc' => 'Administrator', 'role_lvl' => 3],
            ['role_name' => 'Manager', 'role_desc' => 'Manager', 'role_lvl' => 2],
            ['role_name' => 'Chairman', 'role_desc' => 'Chairman', 'role_lvl' => 3],
            ['role_name' => 'Employee', 'role_desc' => 'Employee', 'role_lvl' => 1],
            ['role_name' => 'HR', 'role_desc' => 'Human Resources', 'role_lvl' => 2],
        ];
        static $i = 0;
        $role = $roles[$i % count($roles)];
        $i++;
        return [
            'role_name' => $role['role_name'],
            'role_desc' => $role['role_desc'],
            'role_lvl' => $role['role_lvl'],
        ];
    }
}
