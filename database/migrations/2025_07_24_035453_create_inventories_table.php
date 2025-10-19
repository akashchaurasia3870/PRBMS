<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->string('item_img_path')->nullable();
            $table->integer('item_qty');
            $table->integer('item_price');
            $table->string('category_id');
            $table->softDeletes(); // Adds 'deleted_at' column
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
