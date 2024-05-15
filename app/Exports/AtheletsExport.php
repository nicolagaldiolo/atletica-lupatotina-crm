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
            new AtheletsExportPage($this->data, 'Situazione atleti dettagliata')
        ];
        //if(Auth::user()->can('viewBudgetRequested', $this->project)){
        //    $sheets[] = new BudgetExportPage($this->project, $this->config, $this->dataRows['rowsDataRequested'], __('Budget Richiesto'));
        //}
    }
}
