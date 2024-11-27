<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Enums\GenderType;
use App\Enums\MemberType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AthleteImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[0]){
            $row_data_nascita = intval($row[10]);
            $birth_date = $row_data_nascita ? Date::excelToDateTimeObject($row_data_nascita) : null;

            $gender = null;
            switch($row[2]){
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

            $fidal_card = trim($row[11]);

            return new Athlete([
                'name' => trim($row[1]),
                'surname' => trim($row[0]),
                'gender' => trim($gender),
                'phone' => trim($row[3]),
                'email' => trim($row[4]),
                'address' => trim($row[6]),
                'zip' => trim($row[7]),
                'city' => trim($row[8]),
                'birth_place' => trim($row[9]),
                'birth_date' => $birth_date,
                'type' => ((Str::lower($fidal_card) == 'simpatizzante') ? MemberType::Supporter : MemberType::Athlete),
                'registration_number' => ((Str::lower($fidal_card) == 'simpatizzante') ? null : $fidal_card),
                '10k' => null,
                'half_marathon' => null,
                'marathon' => null
            ]);
        }
    }
}
