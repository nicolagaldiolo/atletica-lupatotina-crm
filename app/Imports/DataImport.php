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
            //'iscrizioni_values' => new SubscriptionImport(),
            //'pagamenti' => new PaymentImport()
            'situazione_soci_values' => new PaymentImportNew()
        ];
    }
}
