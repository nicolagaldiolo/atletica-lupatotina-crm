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

        $athleteRows = collect($collection->reduce(function($arr, $item) use(&$athlete){
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
            
        }, []))->groupBy('athlete_id');

        $athleteRows->each(function($rows, $athleteKey){

            $fees_to_pay = collect([]);
            $resume = 0;

            $athlete = Athlete::findOrFail($athleteKey);

            if($athlete->id == 5){
                $i = "test";
            }

            $rows->each(function($item) use($athlete, $fees_to_pay, &$resume){
                
                if($item['causal'] != 'Pagato'){
                    $row_fee_amount = $item['amount'];
                    if(false && $row_fee_amount < 0){
                        dd("c'è qualcosa che non va");
                    }else{
                        $race_name = (explode(' - ', trim($item['causal'])))[0];
                        $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();
                        
                        $fees_to_pay->put($fee->id, $fee);

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
                    $resume = ($resume + abs($item['amount']));

                    $fees_to_pay->each(function($fee, $feeKey) use($athlete, $item, $fees_to_pay, &$resume){
                        $athletefee = $athlete->fees()->findOrFail($fee->id)->athletefee;
                        if($resume >= $athletefee->custom_amount){
                            //ho budget per pagare la gara, registro il pagamento e scalo il residuo
                            $resume = ($resume - $athletefee->custom_amount);
                            $athletefee->update([
                                'payed_at' => $item['date']
                            ]);
                            $fees_to_pay->forget($feeKey);
                        }//else{
                        //    dd($athlete->fullname, "c'è qualcosa che non va, nn ho più soldi");
                        //}
                    });
                }
            });

            //if($resume > 0 && $athlete->id != 48 && $athlete->id != 59){
            //if($resume > 0){
            //    $i = $athlete;
            //    dd($athlete->id, $athlete->fullname, "c'è qualcosa che non va, ho pagato di più");
            //}
        
        });
    }
}
