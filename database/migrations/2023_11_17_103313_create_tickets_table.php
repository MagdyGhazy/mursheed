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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('title');
            $table->foreignId('user_id')->constrained('mursheed_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->default(-1);
            $table->enum('priority', ['high', 'mid', 'low'])->default('low');
            $table->enum('type', ['sales', 'issue', 'inquire'])->default('inquire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
