<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call(AuthTableSeeder::class);
        $this->call(CertificateSeeder::class);

        Athlete::each(function($athlete){
            Voucher::factory()->create([
                'athlete_id' => $athlete->id
            ]);
        });

        Schema::enableForeignKeyConstraints();
    }
}
