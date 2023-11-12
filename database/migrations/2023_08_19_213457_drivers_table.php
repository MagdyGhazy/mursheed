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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->bigInteger('country_id');
            $table->bigInteger('state_id');
            $table->string('password');

            $table->tinyInteger("gender");
            $table->string('phone');
            $table->string('bio');
            $table->string('car_number');
            $table->string('driver_licence_number');
            $table->string('gov_id');
            $table->tinyInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
