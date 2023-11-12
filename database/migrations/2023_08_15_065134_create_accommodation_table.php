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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();

            $table->json('name');
            $table->json('owner_info');
            $table->json('description');
            $table->json('address');

            $table->integer("country_id");
            $table->integer("state_id");
            $table->integer("city_id");

            $table->tinyInteger("aval_status");
            $table->tinyInteger("info_status");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation');
    }
};
