<?php

namespace App\Exports;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ProceedExportPage implements FromView, WithColumnFormatting, ShouldAutoSize, WithStyles, WithTitle
{
    use Exportable;

    private $proceeds;
    private $title;

    public function __construct($proceeds, $title)
    {
        $this->title = Str::ucfirst($title);
        $this->proceeds = $proceeds;
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
        return view('backend.proceeds.export', [
            'proceeds' => $this->proceeds
        ]);
    }
}
