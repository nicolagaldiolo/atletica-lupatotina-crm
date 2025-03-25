<?php

use App\Enums\MemberType;
use App\Enums\RaceType;
use App\Models\Race;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->timestamp('date')->nullable()->change();
            $table->string('type')->after('name');
        });

        DB::table('races')->update(['type' => RaceType::Race]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
