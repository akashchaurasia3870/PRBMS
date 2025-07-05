<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up()
    {
        Schema::table('users_leave', function (Blueprint $table) {
            $table->string('rejection_reason', 255)->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('users_leave', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
