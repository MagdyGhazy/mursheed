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
        Schema::table('tourists', function (Blueprint $table) {
            $table->unsignedBigInteger('dest_city_id')->nullable()->index();
            $table->string('nationality')->nullable();
            $table->bigInteger('gender')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourists', function (Blueprint $table) {
            $table->bigInteger('gender');
            $table->dropColumn([
                'dest_city_id',
                'nationality'
            ]);
        });
    }
};
