<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Certificate::truncate();

        Athlete::each(function($athlete){
            for($i = 0; $i<9; $i++){
                Certificate::factory()->create([
                    'athlete_id' => $athlete->id,
                    'expires_on' => Carbon::now()->endOfYear()->subYears($i),
                    'is_current' => $i == 0,

                ]); 
            }
        });
        

    }
}