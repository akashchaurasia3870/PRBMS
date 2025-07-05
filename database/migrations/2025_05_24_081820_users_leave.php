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
        Schema::create('users_leave',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('leave_type',['sick','casual','earned','unpaid']);
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->string('reason',100);
            $table->string('description',255);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('deleted')->default(false);
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_leave');
    }
};
