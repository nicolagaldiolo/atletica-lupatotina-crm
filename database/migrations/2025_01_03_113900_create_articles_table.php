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
            Permissions::ListArticles,
            Permissions::ViewArticles,
            Permissions::CreateArticles,
            Permissions::EditArticles,
            Permissions::DeleteArticles
        ];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 14, 2)->default(0);
            $table->text('variants')->nullable(); // Non è possibile utilizzare il campo json perchè la versione di MySql è troppo vecchia per supportarlo
            $table->boolean('is_active')->default(true);
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

        Schema::dropIfExists('articles');
    }
};
