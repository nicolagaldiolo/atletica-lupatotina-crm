<?php

use App\Classes\Utility;
use App\Enums\Permissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $permissions = [];

    public function __construct()
    {
        $this->permissions = [
        ];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->onDelete('set null');
        });

        Utility::manageDbPermissions($this->permissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Utility::manageDbPermissions($this->permissions, true);

        Schema::dropIfExists('order_rows');
    }
};
