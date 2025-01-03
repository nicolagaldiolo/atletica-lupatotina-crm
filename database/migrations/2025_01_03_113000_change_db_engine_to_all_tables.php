<?php

use App\Classes\Utility;
use App\Enums\Permissions;
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
        $engine = config('database.connections.mysql.engine');
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $key => $value){
                DB::statement("ALTER TABLE {$value} ENGINE = {$engine}");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $key => $value){
                DB::statement("ALTER TABLE {$value} ENGINE = MyISAM");
            }
        }
    }
};
