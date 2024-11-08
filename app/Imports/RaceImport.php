<?php

namespace App\Imports;

use App\Models\Race;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RaceImport implements OnEachRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $row_data = intval($row[4]);
        $date = $row_data ? Date::excelToDateTimeObject($row_data) : null;

        $row_iscrizioni_aperte_fino_al = intval($row[6]);
        $subscrible_expiration = $row_iscrizioni_aperte_fino_al ? Date::excelToDateTimeObject($row_iscrizioni_aperte_fino_al) : null;

        $gara = trim($row[2]);

        if($gara){
            Race::create([
                'name' => $gara,
                'distance' => $row[3],
                'date' => $date,
                'is_subscrible' => ($row[5] == 'sì'),
                'subscrible_expiration' => $subscrible_expiration,
            ])->fees()->create([
                'name' => __('Quota base'),
                'expired_at' => $subscrible_expiration,
                'amount' => $row[8]
            ]);
        }

    }
}
