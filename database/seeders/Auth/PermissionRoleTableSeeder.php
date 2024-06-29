<?php

namespace Database\Seeders\Auth;

use App\Enums\Roles;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        //Schema::disableForeignKeyConstraints();

        // Create Permissions
        Permission::firstOrCreate(['name' => 'view_backend']);
        Permission::firstOrCreate(['name' => 'edit_settings']);
        Permission::firstOrCreate(['name' => 'view_logs']);

        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        // Create Roles
        Role::create(['name' => Roles::SuperAdmin]);
        
        Role::create(['name' => Roles::Administrator])
            ->givePermissionTo(Permission::all());
        
        Role::create(['name' => Roles::Athlete])
            ->givePermissionTo('view_backend');
        
        //Schema::enableForeignKeyConstraints();
    }
}
