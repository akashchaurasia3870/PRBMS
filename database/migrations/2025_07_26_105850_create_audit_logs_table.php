<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('auditable_id');
            $table->string('auditable_type');

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();       // 👈 explicit nullable
            $table->string('action');
            $table->json('changes')->nullable();                                           // 👈 explicit nullable
            $table->text('remarks')->nullable();                                           // 👈 explicit nullable

            $table->timestamp('created_at')->useCurrent();

            // Indexes for performance
            $table->index(['auditable_id', 'auditable_type']);
            $table->index('user_id');
            $table->index('action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};

