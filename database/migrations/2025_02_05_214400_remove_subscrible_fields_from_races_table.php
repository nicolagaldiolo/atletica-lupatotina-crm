<?php

use App\Enums\MemberType;
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
        Schema::table('races', function (Blueprint $table) {
            $table->dropColumn('is_subscrible');
            $table->dropColumn('subscrible_expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->boolean('is_subscrible')->default(0)->after('date');
            $table->timestamp('subscrible_expiration')->nullable()->after('is_subscrible');
        });
    }
};
