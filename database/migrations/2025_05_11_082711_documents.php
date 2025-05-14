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
            $table->enum('doc_type', ['Phote_Passport_Size','Aadhar_Card','Driver_licence','HighSchool','Intermediate','Pen_Card']);
            $table->string('doc_desc', 255);
            $table->string('doc_url');
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
        Schema::dropIfExists('documents');
    }
};
