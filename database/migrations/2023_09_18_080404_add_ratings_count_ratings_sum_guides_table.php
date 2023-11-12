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
        Schema::table('guides', function (Blueprint $table) {
            $table->after("admin_rating", function () use ($table) {
                $table->unsignedInteger("ratings_count")->default(0);
                $table->unsignedInteger("ratings_sum")->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn([
                "ratings_count",
                "ratings_sum"
            ]);
        });
    }
};
