<?php

namespace App\Console\Commands;

use App\Imports\CertificateImport;
use App\Imports\DataImport;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\Certificate;
use App\Models\Fee;
use App\Models\Race;
use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importazione dati atleti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            AthleteFee::truncate();
            Athlete::truncate();
            Fee::truncate();
            Race::truncate();
            Voucher::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Excel::import(new DataImport, 'data.xlsx');

            $this->info('Importazione dati avvenuta con successo');

            Certificate::all()->each(function($certificate){
                $certificate->forceDelete();
            });

            Excel::import(new CertificateImport, 'certificates.xlsx');
            $this->info('Importazione certificati avvenuta con successo');
            
        }catch(Throwable $e){
            $this->error('Qualcosa è andato storto: ' . $e->getMessage());
        }
    }
}
