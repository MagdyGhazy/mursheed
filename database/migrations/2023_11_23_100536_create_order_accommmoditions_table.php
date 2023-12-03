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
        Schema::create('order_accommmoditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_id')->references('id')->on('tourists')
                ->onDelete('cascade');
            $table->foreignId('accommmodition_id')->references('id')->on('accommodations')
                ->onDelete('cascade');
            $table->decimal('price');
            $table->decimal('total_cost');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_accommmoditions');
    }
};
