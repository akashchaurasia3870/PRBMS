<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollReceiptsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->integer('total_working_days');
            $table->integer('present_days');
            $table->integer('leave_days')->default(0);
            $table->decimal('total_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->enum('status', ['generated', 'paid'])->default('generated');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_receipts');
    }
}
