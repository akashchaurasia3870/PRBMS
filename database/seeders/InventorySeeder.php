<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Category;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        $items = [
            ['item_code' => 'ELEC-LAP-001', 'item_name' => 'Dell Laptop XPS 13', 'category' => 'Electronics', 'price' => 1299.99, 'qty' => 5, 'min_stock' => 2, 'location' => 'Warehouse A-1', 'supplier' => 'Dell Inc'],
            ['item_code' => 'ELEC-MON-002', 'item_name' => 'Samsung 27" Monitor', 'category' => 'Electronics', 'price' => 299.99, 'qty' => 12, 'min_stock' => 3, 'location' => 'Warehouse A-2', 'supplier' => 'Samsung'],
            ['item_code' => 'FURN-CHR-003', 'item_name' => 'Ergonomic Office Chair', 'category' => 'Furniture', 'price' => 249.99, 'qty' => 8, 'min_stock' => 2, 'location' => 'Warehouse B-1', 'supplier' => 'Herman Miller'],
            ['item_code' => 'FURN-DSK-004', 'item_name' => 'Standing Desk Adjustable', 'category' => 'Furniture', 'price' => 399.99, 'qty' => 6, 'min_stock' => 1, 'location' => 'Warehouse B-2', 'supplier' => 'IKEA'],
            ['item_code' => 'OFF-PEN-005', 'item_name' => 'Blue Ballpoint Pens (Pack of 12)', 'category' => 'Office Supplies', 'price' => 8.99, 'qty' => 50, 'min_stock' => 10, 'location' => 'Storage C-1', 'supplier' => 'BIC'],
            ['item_code' => 'OFF-PAP-006', 'item_name' => 'A4 Copy Paper (500 sheets)', 'category' => 'Office Supplies', 'price' => 12.99, 'qty' => 25, 'min_stock' => 5, 'location' => 'Storage C-2', 'supplier' => 'Staples'],
            ['item_code' => 'TOOL-DRL-007', 'item_name' => 'Cordless Drill 18V', 'category' => 'Tools & Equipment', 'price' => 89.99, 'qty' => 4, 'min_stock' => 1, 'location' => 'Tool Room D-1', 'supplier' => 'DeWalt'],
            ['item_code' => 'TOOL-HAM-008', 'item_name' => 'Claw Hammer 16oz', 'category' => 'Tools & Equipment', 'price' => 19.99, 'qty' => 15, 'min_stock' => 3, 'location' => 'Tool Room D-2', 'supplier' => 'Stanley'],
            ['item_code' => 'ELEC-PHN-009', 'item_name' => 'iPhone 15 Pro 256GB', 'category' => 'Electronics', 'price' => 1099.99, 'qty' => 3, 'min_stock' => 1, 'location' => 'Secure Storage E-1', 'supplier' => 'Apple Inc'],
            ['item_code' => 'ELEC-TAB-010', 'item_name' => 'iPad Air 11" 128GB', 'category' => 'Electronics', 'price' => 599.99, 'qty' => 7, 'min_stock' => 2, 'location' => 'Secure Storage E-2', 'supplier' => 'Apple Inc'],
            ['item_code' => 'BOOK-MAN-011', 'item_name' => 'Project Management Handbook', 'category' => 'Books & Media', 'price' => 29.99, 'qty' => 20, 'min_stock' => 5, 'location' => 'Library F-1', 'supplier' => 'McGraw Hill'],
            ['item_code' => 'BOOK-TEC-012', 'item_name' => 'JavaScript Complete Guide', 'category' => 'Books & Media', 'price' => 39.99, 'qty' => 18, 'min_stock' => 4, 'location' => 'Library F-2', 'supplier' => "O'Reilly"],
            ['item_code' => 'MED-KIT-013', 'item_name' => 'First Aid Kit Complete', 'category' => 'Medical Supplies', 'price' => 49.99, 'qty' => 10, 'min_stock' => 3, 'location' => 'Medical Room G-1', 'supplier' => 'Johnson & Johnson'],
            ['item_code' => 'MED-BAN-014', 'item_name' => 'Adhesive Bandages (100 pack)', 'category' => 'Medical Supplies', 'price' => 12.99, 'qty' => 30, 'min_stock' => 8, 'location' => 'Medical Room G-2', 'supplier' => 'Band-Aid'],
            ['item_code' => 'AUTO-OIL-015', 'item_name' => 'Motor Oil 5W-30 (5L)', 'category' => 'Automotive', 'price' => 24.99, 'qty' => 12, 'min_stock' => 3, 'location' => 'Garage H-1', 'supplier' => 'Mobil 1']
        ];

        foreach ($items as $item) {
            $category = $categories->where('name', $item['category'])->first();
            if ($category) {
                Inventory::create([
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'item_description' => 'High-quality ' . strtolower($item['item_name']) . ' for professional use.',
                    'item_price' => $item['price'],
                    'item_qty' => $item['qty'],
                    'min_stock_level' => $item['min_stock'],
                    'max_stock_level' => $item['qty'] * 3,
                    'location' => $item['location'],
                    'supplier' => $item['supplier'],
                    'barcode' => 'BC' . str_pad(rand(1, 999999999999), 12, '0', STR_PAD_LEFT),
                    'category_id' => $category->id,
                    'created_by' => 1
                ]);
            }
        }
    }
}