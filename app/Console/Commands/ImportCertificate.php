<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;
use App\Imports\CertificateImport;
use Maatwebsite\Excel\Facades\Excel;
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

            Excel::import(new CertificateImport, 'certificates.xlsx');

            $this->info('Importazione certificati avvenuta con successo');
        }catch(Throwable $e){
            $this->error('Qualcosa è andato storto: ' . $e->getMessage());
        }
    }
}
