<?php

namespace App\Imports;

use App\Enums\VoucherType;
use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
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
                    // Qui ho cambiato atleta
                    $athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $item[0])->firstOrFail();                        
                }
                if($athlete){
                    $arr[] = [
                        'date' => $date_obj,
                        'athlete_id' => $athlete->id,
                        'athlete' => $athlete,
                        'causal' => $item[2],
                        'amount' => $item[3]
                    ];
                }
            }
            return $arr;
        }, []))->groupBy('athlete_id');

        $athleteRows->each(function($rows, $athleteKey){

            // CASI NON GESTITI 
            //12 - Brunelli Alberto,
            //37 - Nicola Galdiolo,
            
            if($athleteKey == 12){

                $fees_to_pay = collect([]);
                $budget = 0;

                $athlete = Athlete::findOrFail($athleteKey);

                $data = collect([]);
                
                // INIZIO CASO PARTICOLARE
                // Potrebbe verificarsi il caso che viene registrata erroneamente un'iscrizione due volte
                // ed è stato registrato un pagamento anche della seconda iscrizione errata.
                // a quel punto rimuovo la seconda iscrizione errata e rimuovo anche il relativo pagamento correttivo
                $exists = collect([]);
                $amount_to_remove = 0;
                
                $rows->each(function($item) use($exists, $data, &$amount_to_remove){
                    if($item['causal'] == 'Pagato'){
                        $data->push($item);
                    }else{
                        if(!$exists->contains($item['causal'])){
                            $data->push($item);
                            $exists->push($item['causal']);
                        }else{
                            $amount_to_remove = $amount_to_remove + $item['amount'];
                        }
                    }
                    
                });

                if($amount_to_remove){
                    $data = $data->map(function($item) use(&$amount_to_remove){
                        if($item['causal'] == 'Pagato' && $amount_to_remove && $item['causal'] >= $amount_to_remove){
                            $item['amount'] = $item['amount'] + $amount_to_remove;
                            $amount_to_remove = 0;
                        }
                        return $item;
                    });
                }
                // FINE CASO PARTICOLARE

                $data->each(function($item, $keyRow) use($athlete, $fees_to_pay, &$budget){
                    
                    if($item['causal'] != 'Pagato'){
                        $row_fee_amount = $item['amount'];
                        if($row_fee_amount < 0){
                            dd("C'è qualcosa che non va, non può essere che la causale sia diversa da pagato e ci sia un importo negativo");
                        }else{

                            $race_name = (explode(' - ', trim($item['causal'])))[0];
                            $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();
                            
                            //$voucher = $athlete->validVouchers()->orderBy('amount', 'desc')->first();
                            
                            $fees_to_pay->put($fee->id, $fee);
                            $athlete->fees()->syncWithoutDetaching(
                                [
                                    $fee->id => [
                                        'custom_amount' => $row_fee_amount,
                                        'created_at' => $item['date'],
                                        //'voucher_id' => $voucher->id ?? null
                                    ]
                                ]
                            );
                        }
        
                    }else{
                        // Se la causale è Pagato e l'importo è positivo si tratta di un errata-corrige di pagamento
                        // quindi lo sottraggo al budget
                        if($item['amount']){
                            $budget = ($budget - $item['amount']);    
                        }else{
                            $budget = ($budget + abs($item['amount']));    
                        }
                        
                        $fees_to_pay->each(function($fee, $feeKey) use($athlete, $item, $fees_to_pay, &$budget){
                            $athletefee = $athlete->fees()->findOrFail($fee->id)->athletefee;
                            if($budget >= $athletefee->custom_amount){
                                //ho budget per pagare la gara, registro il pagamento e scalo il residuo
                                $budget = ($budget - $athletefee->custom_amount);
                                $athletefee->update([
                                    'payed_at' => $item['date']
                                ]);
                                $fees_to_pay->forget($feeKey);
                            }
                        });
                    }
                });

                if($budget > 0){
                    // Se ho ancora budget emetto un voucher del valore del budget
                    $voucher = $athlete->vouchers()->create([
                        'name' => 'Buono gara',
                        'type' => VoucherType::Credit,
                        'amount' => $budget
                    ]);
                    //$i = 10;
                    dd($athlete->id, $athlete->fullname, "c'è qualcosa che non va, ho pagato di più");
                }
            }
        
        });
    }
}
