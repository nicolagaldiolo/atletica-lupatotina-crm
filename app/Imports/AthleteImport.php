<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Enums\GenderType;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AthleteImport implements ToModel, WithStartRow, WithHeadingRow
{

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row_data_nascita = intval($row['data_nascita']);
        $birth_date = $row_data_nascita ? Date::excelToDateTimeObject($row_data_nascita) : null;

        $gender = null;
        switch($row['sesso']){
            case 'M':
                $gender = GenderType::Male;
                break;
            case 'F':
                $gender = GenderType::Female;
                break;
            default:
                $gender = GenderType::Other;
                break;
        }

        return new Athlete([
            'name' => trim($row['nome']),
            'surname' => trim($row['cognome']),
            'gender' => trim($gender),
            'phone' => trim($row['telefono']),
            'email' => trim($row['email']),
            'address' => trim($row['indirizzo']),
            'zip' => trim($row['cap']),
            'city' => trim($row['comune']),
            'birth_place' => trim($row['luogo_nascita']),
            'birth_date' => $birth_date,
            'registration_number' => trim($row['tessera_fidal']),
            '10k' => trim($row['10mila']),
            'half_marathon' => trim($row['mezza']),
            'marathon' => trim($row['maratona'])
        ]);
    }
}
