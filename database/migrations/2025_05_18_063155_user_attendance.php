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
        Schema::create('attendance',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status',['present','absent','late','on_leave','work_from_home']);
            $table->timestamp('check_in_time')->useCurrent();
            $table->timestamp('check_out_time')->useCurrent();
            $table->timestamp('date')->useCurrent();
            $table->string('created_by', 50)->nullable();
            $table->string('update_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
