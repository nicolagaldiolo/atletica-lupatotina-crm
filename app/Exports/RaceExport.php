<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RaceExport implements WithMultipleSheets
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new RaceExportSimple($this->data, 'Partecipazione gare'),
            new RaceExportDetailed($this->data, 'Partecipazione gare dettagliata'),
        ];
    }
}
