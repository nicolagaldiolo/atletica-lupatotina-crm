<?php

use App\Classes\Utility;
use App\Enums\AthletePermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $permissions = [];

    public function __construct()
    {
        $this->permissions = AthletePermission::asArray();
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
