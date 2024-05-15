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
        Schema::create('athlete_race', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained()->onDelete('cascade');
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_id')->constrained()->onDelete('cascade');
            $table->timestamp('subscription_at')->nullable();
            $table->timestamps();

            $table->unique(['athlete_id', 'race_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athlete_race');
    }
};
