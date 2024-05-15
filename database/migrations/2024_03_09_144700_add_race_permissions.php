<?php

use App\Classes\Utility;
use App\Enums\RacePermission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $permissions = [];

    public function __construct()
    {
        $this->permissions = RacePermission::asArray();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Utility::manageDbPermissions($this->permissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Utility::manageDbPermissions($this->permissions, true);
    }
};
