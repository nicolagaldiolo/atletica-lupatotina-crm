<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
use Deployer\Logger\Logger;
use Exception;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class PaymentImportNew implements ToCollection, WithStartRow
{
    private $payments = [];

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   

        $athlete = null;

        $rows = collect($collection->reduce(function($arr, $item) use(&$athlete){
            $date_item = (int) $item[1];
            $date_obj = $date_item ? Date::excelToDateTimeObject($date_item) : null;
            if($date_obj){
                // Solo se la data è valida tratto la riga come riga valida

                if($item[0]){
                    $athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $item[0])->firstOrFail();    
                    // Qui ho cambiato atleta
                }

                if($athlete){

                    $data = [
                        'date' => $date_obj,
                        'athlete_id' => $athlete->id,
                        'athlete' => $athlete,
                        'causal' => $item[2],
                        'amount' => $item[3]
                    ];

                    $arr[] = $data;                    
                }
            }

            return $arr;
            
        }, []));

        $rows->each(function($item){
            
            if($item['causal'] != 'Pagato'){
                $row_fee_amount = $item['amount'];
                if($row_fee_amount < 0){
                    dd("c'è qualcosa che non va");
                }else{
                    $race_name = (explode(' - ', trim($item['causal'])))[0];
                    $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();
                    
                    $athlete = $item['athlete'];

                    $athlete->fees()->syncWithoutDetaching(
                        [
                            $fee->id => [
                                'custom_amount' => $row_fee_amount,
                                'created_at' => $item['date']
                            ]
                        ]
                    );
                }

            }else{
                $i = "devi registrare il pagamento";
            }
        });
    }
}
