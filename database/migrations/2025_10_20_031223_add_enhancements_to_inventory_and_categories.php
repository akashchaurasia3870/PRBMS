<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add enhancements to inventories table
        Schema::table('inventories', function (Blueprint $table) {
            $table->integer('min_stock_level')->nullable()->after('item_qty');
            $table->integer('max_stock_level')->nullable()->after('min_stock_level');
            $table->string('barcode')->nullable()->after('item_code');
            $table->string('location')->nullable()->after('item_description');
            $table->string('supplier')->nullable()->after('location');
            $table->date('expiry_date')->nullable()->after('supplier');
        });

        // Add enhancements to categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->string('color', 7)->default('#3B82F6')->after('code');
            $table->string('icon', 50)->default('📦')->after('color');
            $table->boolean('is_active')->default(true)->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['min_stock_level', 'max_stock_level', 'barcode', 'location', 'supplier', 'expiry_date']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon', 'is_active']);
        });
    }
};