<?php

namespace App\Imports;

use App\Models\Athlete;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CertificateImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        
        $rows->each(function($row){
            $name = str_replace(' ', '', $row['nome']);
            if($name){
                $athlete = Athlete::whereRaw('REPLACE (CONCAT(surname,name)," ", "") like ?', ["%{$name}%"])->first();
                $expire_on = ($row['scadcertificato'] && intval($row['scadcertificato'])) ? Date::excelToDateTimeObject($row['scadcertificato']) : null;
                if($athlete && $expire_on){
                    $athlete->certificate()->create([
                        'expires_on' => $expire_on,
                        'is_current' => true
                    ]);

                }
            }
        });
    }
}
