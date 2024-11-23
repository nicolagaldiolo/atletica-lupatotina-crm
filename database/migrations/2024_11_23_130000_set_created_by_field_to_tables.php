<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tables = [
        'athletes',
        'athlete_fee',
        'certificates',
        'fees',
        'races',
        'vouchers'
    ];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach($this->tables as $item){
            Schema::table($item, function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
                $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach($this->tables as $item){
            Schema::table($item, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
                $table->dropForeign(['deleted_by']);
                $table->dropColumn('deleted_by');
            });
        }
    }
};
