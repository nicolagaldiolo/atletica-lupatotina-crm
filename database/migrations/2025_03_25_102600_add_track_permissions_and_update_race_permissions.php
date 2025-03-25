<?php

use App\Classes\Utility;
use App\Enums\Permissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    protected $permissions = [];

    public function __construct()
    {
        $this->permissions = [
            Permissions::ListTrack,
            Permissions::ViewTrack,
            Permissions::CreateTrack,
            Permissions::EditTrack,
            Permissions::DeleteTrack,
            Permissions::ReportTrack,
            Permissions::HandlePaymentsTrack,
            Permissions::DeductPaymentsTrack,
            Permissions::HandleSubscriptionsTrack
        ];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Utility::manageDbPermissions($this->permissions);

        DB::table('permissions')
            ->where('name', 'handle_payments')
            ->update(['name' => 'handle_payments_race']);

        DB::table('permissions')
        ->where('name', 'deduct_payments')
        ->update(['name' => 'deduct_payments_race']);

        DB::table('permissions')
        ->where('name', 'handle_subscriptions')
        ->update(['name' => 'handle_subscriptions_race']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')
            ->where('name', 'handle_payments_race')
            ->update(['name' => 'handle_payments']);

        DB::table('permissions')
        ->where('name', 'deduct_payments_race')
        ->update(['name' => 'deduct_payments']);

        DB::table('permissions')
        ->where('name', 'handle_subscriptions_race')
        ->update(['name' => 'handle_subscriptions']);

        Utility::manageDbPermissions($this->permissions, true);
    }
};
