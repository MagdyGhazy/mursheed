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
        Schema::table('drivers', function (Blueprint $table) {
            $table->tinyInteger("gender")->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('bio')->nullable()->change();
            $table->string('car_number')->nullable()->change();
            $table->string('driver_licence_number')->nullable()->change();
            $table->string('gov_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->tinyInteger("gender")->change();
            $table->string('phone')->change();
            $table->string('bio')->change();
            $table->string('car_number')->change();
            $table->string('driver_licence_number')->change();
            $table->string('gov_id')->change();
        });
    }
};
