<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            'nomi' => new AthleteImport(),
            'gare' => new RaceImport(),
            'situazione_soci' => new PaymentImport()            
        ];
    }
}
