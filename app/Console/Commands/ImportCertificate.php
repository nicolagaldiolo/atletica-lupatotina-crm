<?php

namespace App\Console\Commands;

use App\Classes\Utility;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Imports\DataImport;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\Certificate;
use App\Models\Fee;
use App\Models\Race;
use App\Models\Role;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Throwable;

class ImportCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importazione certificati atleti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            Certificate::all()->each(function($certificate){
                $certificate->forceDelete();
            });

            /*
            //Excel::import(new DataImport, 'data.xlsx');

            Athlete::each(function($athlete){
                for($i = 0; $i<9; $i++){
                    Certificate::factory()->create([
                        'athlete_id' => $athlete->id,
                        'expires_on' => Carbon::now()->endOfYear()->subYears($i),
                        'is_current' => $i == 1,
                    ]); 
                }
            });
            */

            $this->info('Importazione certificati avvenuta con successo');
        }catch(Throwable $e){
            $this->error('Qualcosa è andato storto: ' . $e->getMessage());
        }
    }
}
