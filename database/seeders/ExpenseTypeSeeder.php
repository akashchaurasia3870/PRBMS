<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseType;

class ExpenseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $expenseTypes = [
            [
                'type' => 'Travel & Transportation',
                'description' => 'All travel related expenses including flights, hotels, taxi, and public transportation'
            ],
            [
                'type' => 'Food & Dining',
                'description' => 'Meals, snacks, and dining expenses for business purposes'
            ],
            [
                'type' => 'Office Supplies',
                'description' => 'Stationery, equipment, and other office-related supplies'
            ],
            [
                'type' => 'Utilities',
                'description' => 'Electricity, water, internet, and other utility bills'
            ],
            [
                'type' => 'Marketing & Advertising',
                'description' => 'Promotional activities, advertisements, and marketing campaigns'
            ],
            [
                'type' => 'Training & Development',
                'description' => 'Employee training, courses, workshops, and skill development programs'
            ],
            [
                'type' => 'Equipment & Hardware',
                'description' => 'Computer equipment, machinery, and other hardware purchases'
            ],
            [
                'type' => 'Maintenance & Repairs',
                'description' => 'Equipment maintenance, repairs, and facility upkeep'
            ],
            [
                'type' => 'Insurance',
                'description' => 'Business insurance, health insurance, and other insurance premiums'
            ],
            [
                'type' => 'Software & Subscriptions',
                'description' => 'Software licenses, SaaS subscriptions, and digital tools'
            ],
            [
                'type' => 'Rent & Facilities',
                'description' => 'Office rent, facility costs, and property-related expenses'
            ],
            [
                'type' => 'Communication & Internet',
                'description' => 'Phone bills, internet services, and communication tools'
            ],
            [
                'type' => 'Professional Services',
                'description' => 'Legal fees, consulting, accounting, and other professional services'
            ],
            [
                'type' => 'Daily Basis Expenses',
                'description' => 'Regular daily operational expenses and miscellaneous costs'
            ],
            [
                'type' => 'Work Related Expenses',
                'description' => 'General work-related expenses and business operational costs'
            ]
        ];

        foreach ($expenseTypes as $expenseType) {
            ExpenseType::create([
                'type' => $expenseType['type'],
                'description' => $expenseType['description'],
                'created_by' => 1,
                'deleted' => 0
            ]);
        }
    }
}