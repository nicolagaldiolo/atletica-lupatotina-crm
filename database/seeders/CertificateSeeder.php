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
            Certificate::factory()->count(10)->create([
                'athlete_id' => $athlete->id
            ]);
        });
        

    }
}