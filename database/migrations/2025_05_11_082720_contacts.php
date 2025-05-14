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
        Schema::create('contacts',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('country')->default('India');
            $table->string('state',50);
            $table->string('city',50);
            $table->string('area',50);
            $table->string('locality',100);
            $table->string('landmark',100);
            $table->string('street',50);
            $table->string('house_no',50);
            $table->string('contact_no',15);
            $table->string('emergency_contact_no',15);
            $table->string('pincode',10);
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
        Schema::dropIfExists('contacts');
    }
};
