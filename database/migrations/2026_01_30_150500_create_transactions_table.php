<?php

use App\Enums\ArticleType;
use App\Enums\MemberType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamp('payed_at')->nullable();
            $table->boolean('bank_transfer')->default(0);
            $table->foreignId('cashed_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamp('deduct_at')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('transactions');
    }
};
