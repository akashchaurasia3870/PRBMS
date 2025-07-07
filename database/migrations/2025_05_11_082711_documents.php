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
        Schema::create('documents',function(Blueprint $table){
            $table->id();
            $table->integer('user_id');
            $table->enum('doc_type', [
                'Photo_Passport_Size',
                'UID_Card',
                'Driver_license',
                'HighSchool',
                'Intermediate',
                'Pen_Card'
            ]);
            $table->string('doc_desc', 255);
            $table->string('doc_url');
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
        Schema::dropIfExists('documents');
    }
};
