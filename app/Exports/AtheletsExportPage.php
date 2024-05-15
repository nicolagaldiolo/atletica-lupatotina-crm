<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AtheletsExportPage implements FromView, WithColumnFormatting, ShouldAutoSize, WithStyles, WithTitle
{
    use Exportable;

    private $data;
    private $title;

    public function __construct($data, $title)
    {
        $this->title = $title;
        $this->data = $data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function view(): View
    {
        return view('backend.reports.export.athlete', [
            'data' => $this->data
        ]);
    }
}
