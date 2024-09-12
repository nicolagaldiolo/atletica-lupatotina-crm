<?php

namespace App\Console\Commands;

use App\Imports\DataImport;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Race;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();
        Athlete::truncate();
        Race::truncate();
        Fee::truncate();
        AthleteFee::truncate();
        //AthleteFee::whereNotNull('id')->update([
        //    'payed_at' => null
        //]);
        
        Schema::enableForeignKeyConstraints();

        Excel::import(new DataImport, 'data.xlsx');

        Artisan::call('db:seed');
    }
}
