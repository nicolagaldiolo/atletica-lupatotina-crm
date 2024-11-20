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
        Schema::table('athlete_fee', function (Blueprint $table) {
            $table->boolean('bank_transfer')->default(0)->after('payed_at');
            $table->foreignId('cashed_by')->nullable()->after('bank_transfer')->constrained('users', 'id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athlete_fee', function (Blueprint $table) {
            $table->dropColumn('bank_transfer');
            $table->dropColumn('cashed_by');
        });
    }
};
