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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('deleted')->default(0)->after('code');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted');
            $table->unsignedBigInteger('created_by')->nullable()->after('deleted_by');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->softDeletes()->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['deleted', 'deleted_by', 'created_by', 'updated_by', 'deleted_at']);
        });
    }
};