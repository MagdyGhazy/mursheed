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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
//            $table->foreignIdFor(\App\Models\Country::class)->constrained();
            $table->bigInteger('country_id');
            $table->bigInteger('state_id');

//            $table->foreignIdFor(\App\Models\City::class)->constrained();
//            $table->foreignIdFor(\App\Models\Language::class)->constrained();
            $table->string('name');

            $table->tinyInteger("gender");
            $table->string('password');
            $table->string('email');
            $table->string('phone');
            $table->string('bio');
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
