<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PaymentImport implements ToCollection, WithStartRow
{
    private $payments = [];

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   
        $collection->each(function($item){
            $timestamp = (float) $item[0];

            if($timestamp){
                $atleta1 = $item[2];
                $atleta1_importo = $item[3];
                if($atleta1 && $atleta1_importo){
                    $this->handleImport($atleta1, $atleta1_importo);
                }

                $atleta2 = $item[4];
                $atleta2_importo = $item[5];
                if($atleta2 && $atleta2_importo){
                    $this->handleImport($atleta2, $atleta2_importo);
                }

                $atleta3 = $item[6];
                $atleta3_importo = $item[7];
                if($atleta3 && $atleta3_importo){
                    $this->handleImport($atleta3, $atleta3_importo);
                }

                $atleta4 = $item[8];
                $atleta4_importo = $item[9];
                if($atleta4 && $atleta4_importo){
                    $this->handleImport($atleta4, $atleta4_importo);
                }

                $atleta5 = $item[10];
                $atleta5_importo = $item[11];
                if($atleta5 && $atleta5_importo){
                    $this->handleImport($atleta5, $atleta5_importo);
                }
            }
        });

        collect($this->payments)->each(function($item, $id){
            $remaining_amount = $item;
            Athlete::findOrFail($id)->feesToPay()->orderBy('amount', 'desc')->get()->each(function($item) use($id, &$remaining_amount){
                //if($id == 5){
                    $amount = $item->amount;
                    if($remaining_amount >= $amount){
                        //$remaining_amount = ($remaining_amount - $amount);
                        $item->athletefee->update([
                            'payed_at' => Carbon::now()
                        ]);
                    }
                //}
            });

            $i = $remaining_amount;
            
        });
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /*
    public function onRow(Row $row)
    {
        $timestamp = intval($row[0]);
        
        //$date = $timestamp ? Date::excelToDateTimeObject($timestamp) : null;

        if($timestamp){

            $atleta1 = $row[2];
            $atleta1_importo = $row[3];
            $atleta2 = $row[4];
            $atleta2_importo = $row[5];
            $atleta3 = $row[6];
            $atleta3_importo = $row[7];
            $atleta4 = $row[8];
            $atleta4_importo = $row[9];
            $atleta5 = $row[10];
            $atleta5_importo = $row[11];
            $i = $timestamp;
            $i = 10;
            
            $row_data = intval($row['data']);
            

            $row_iscrizioni_aperte_fino_al = intval($row['iscrizioni_aperte_fino_al']);
            $subscrible_expiration = $row_iscrizioni_aperte_fino_al ? Date::excelToDateTimeObject($row_iscrizioni_aperte_fino_al) : null;

            $gara = trim($row['gara']);

            if($gara){
                Race::create([
                    'name' => $gara,
                    'distance' => $row['distanza'],
                    'date' => $date,
                    'is_subscrible' => ($row['iscrizioni_aperte'] == 'sì'),
                    'subscrible_expiration' => $subscrible_expiration,
                ])->fees()->create([
                    'name' => __('Quota base'),
                    'expired_at' => $subscrible_expiration,
                    'amount' => $row['costo']
                ]);
            }
            
        }

    }
    */

    private function handleImport($atleta1, $atleta1_importo)
    {
        $athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $atleta1)->firstOrFail();
        if(!array_key_exists($athlete->id, $this->payments)){
            $this->payments[$athlete->id] = 0;
        }
        
        $this->payments[$athlete->id] = $this->payments[$athlete->id] + $atleta1_importo;
    }
}
