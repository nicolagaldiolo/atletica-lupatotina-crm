<?php

namespace App\Exports;

use App\Models\Proceed;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProceedExport implements WithMultipleSheets
{
    private $accounts;
    private $raceType;

    public function __construct($accounts, $raceType)
    {
        $this->accounts = $accounts;
        $this->raceType = $raceType;
    }

    public function sheets(): array
    {
        return collect($this->accounts)->reduce(function($arr, $item, $key){
            $arr[] = new ProceedExportPage($item, $key, $this->raceType);
            return $arr;
        }, []);
    }
}
