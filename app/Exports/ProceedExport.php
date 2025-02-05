<?php

namespace App\Exports;

use App\Models\Proceed;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProceedExport implements WithMultipleSheets
{
    private $accounts;

    public function __construct($accounts)
    {
        $this->accounts = $accounts;
    }

    public function sheets(): array
    {
        return collect($this->accounts)->reduce(function($arr, $item, $key){
            $arr[] = new ProceedExportPage($item, $key);
            return $arr;
        }, []);
    }
}
