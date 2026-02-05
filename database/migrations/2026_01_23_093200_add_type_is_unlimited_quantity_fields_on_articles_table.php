<?php

use App\Enums\ArticleType;
use App\Enums\MemberType;
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
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->after('price');
            $table->enum('type', ArticleType::asArray())->default(ArticleType::Simple)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('type');    
        });
    }
};
