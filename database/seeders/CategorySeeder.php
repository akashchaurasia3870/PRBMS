<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'code' => 'ELEC',
                'description' => 'Electronic devices, computers, phones, and related accessories',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Furniture',
                'code' => 'FURN',
                'description' => 'Office and home furniture including desks, chairs, tables, and storage',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Office Supplies',
                'code' => 'OFF',
                'description' => 'Stationery, paper, pens, and general office consumables',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Tools & Equipment',
                'code' => 'TOOL',
                'description' => 'Hand tools, power tools, and specialized equipment',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Clothing & Apparel',
                'code' => 'CLTH',
                'description' => 'Uniforms, safety clothing, and general apparel items',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Books & Media',
                'code' => 'BOOK',
                'description' => 'Books, magazines, DVDs, and educational materials',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Medical Supplies',
                'code' => 'MED',
                'description' => 'First aid supplies, medical equipment, and health-related items',
                'created_by' => 1,
                'deleted' => 0
            ],
            [
                'name' => 'Automotive',
                'code' => 'AUTO',
                'description' => 'Vehicle parts, maintenance supplies, and automotive accessories',
                'created_by' => 1,
                'deleted' => 0
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}