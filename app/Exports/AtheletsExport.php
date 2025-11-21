<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AtheletsExport implements WithMultipleSheets
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            //new AtheletsExportPage($this->data['data'], 'Situazione atleti dettagliata'),
            new AtheletsExportRaceSimple($this->data['races'], 'Partecipazione gare')
        ];
    }
}
