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
        /*
        Certificate::factory()
        Athlete::each(function($athlete){
            $athlete->certificates()->factory()->count(5)->make();
        });
        */

        Athlete::each(function($athlete){
            $athlete->certificates()->create([
                'document' => 'aaa',
                'expires_on' => Carbon::now()
            ]);
        });
    }
}