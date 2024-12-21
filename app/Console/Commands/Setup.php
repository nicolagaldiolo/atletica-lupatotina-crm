<?php

namespace App\Console\Commands;

use App\Classes\Utility;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Imports\DataImport;
use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Role;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Throwable;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup applicazioneCommand description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            Artisan::call('migrate:fresh --force');

            // Reset cached roles and permissions
            //Artisan::call('permission:cache-reset');
            

            $permissions = Permissions::asArray();
            Utility::manageDbPermissions($permissions);

            collect(Roles::asArray())->each(function($item, $key){
                $permissions = [];
                switch ($item) {
                    case Roles::SuperAdmin:
                        $permissions = Permissions::asArray();
                        break;
                    case Roles::Administrator:
                        $permissions = [
                            Permissions::ViewDashboard,
                            Permissions::ListAthletes,
                            Permissions::ViewAthletes,
                            Permissions::CreateAthletes,
                            Permissions::EditAthletes,
                            Permissions::DeleteAthletes,
                            Permissions::ReportAthletes,
                            Permissions::InviteAthletes,
                            Permissions::ListVouchers,
                            Permissions::ViewVouchers,
                            Permissions::CreateVouchers,
                            Permissions::EditVouchers,
                            Permissions::DeleteVouchers,
                            Permissions::ListCertificates,
                            Permissions::ViewCertificates,
                            Permissions::CreateCertificates,
                            Permissions::EditCertificates,
                            Permissions::DeleteCertificates,
                            Permissions::ListRaces,
                            Permissions::ViewRaces,
                            Permissions::CreateRaces,
                            Permissions::EditRaces,
                            Permissions::DeleteRaces,
                            Permissions::ReportRaces,
                            Permissions::HandlePayments,
                            Permissions::DeductPayments,
                            Permissions::HandleSubscriptions,
                            Permissions::ListUsers,
                            Permissions::ViewUsers,
                            Permissions::CreateUsers,
                            Permissions::EditUsers,
                            Permissions::DeleteUsers,
                            Permissions::BlockUsers,
                            Permissions::ListRoles,
                            Permissions::ViewRoles,
                            Permissions::CreateRoles,
                            Permissions::EditRoles,
                            Permissions::DeleteRoles,
                            Permissions::AssignRoles
                        ];
                        break;
                    case Roles::Manager:
                        $permissions = [
                            Permissions::ViewDashboard,
                            Permissions::ListAthletes,
                            Permissions::ViewAthletes,
                            Permissions::CreateAthletes,
                            Permissions::EditAthletes,
                            Permissions::DeleteAthletes,
                            Permissions::ReportAthletes,
                            Permissions::ListRaces,
                            Permissions::ViewRaces,
                            Permissions::CreateRaces,
                            Permissions::EditRaces,
                            Permissions::DeleteRaces,
                            Permissions::ReportRaces,
                            Permissions::ListVouchers,
                            Permissions::ViewVouchers,
                            Permissions::CreateVouchers,
                            Permissions::EditVouchers,
                            Permissions::DeleteVouchers,
                            Permissions::HandlePayments,
                            Permissions::DeductPayments,
                            Permissions::HandleSubscriptions
                        ];
                        break;
                    case Roles::Accountant:
                        $permissions = [
                            Permissions::ViewDashboard,
                            Permissions::ListAthletes,
                            Permissions::HandlePayments,
                        ];
                        break;
                    case Roles::Healthcare:
                        $permissions = [
                            Permissions::ViewDashboard,
                            Permissions::ListAthletes,
                            Permissions::ListCertificates,
                            Permissions::ViewCertificates,
                            Permissions::CreateCertificates,
                            Permissions::EditCertificates,
                            Permissions::DeleteCertificates
                        ];
                        break;
                    case Roles::User:
                        $permissions = [
                            Permissions::ViewDashboard
                        ];
                        break;
                }

                Role::create(['name' => $item])
                    ->givePermissionTo($permissions);

                $email = Str::slug($item) . '@domain.com';
                $password = 'secret';

                if($item == Roles::SuperAdmin || App::environment('local')){

                    if($item == Roles::SuperAdmin){
                        $email = env('SUPER_ADMIN_USERNAME', $email);
                        $password = env('SUPER_ADMIN_PASSWORD', $password);
                    }
                    
                    User::create([
                        'name' => $key,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ])->assignRole($item);
                };    
            });

            $this->info('Setup applicazione avvenuta con successo');

        }catch(Throwable $e){
            $this->error('Qualcosa è andato storto: ' . $e->getMessage());
        }
    }
}
