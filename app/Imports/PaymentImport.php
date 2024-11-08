<?php

namespace App\Imports;

use App\Enums\VoucherType;
use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;

class PaymentImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   
        // 1 MI CREO LA STRUTTURA DATI DA ELABORARE
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

        // 2 GIRO GLI ATLETI E GESTISCO LE RELATIVE ISCRIZIONI/PAGAMENTI
        $athleteRows->each(function($rows, $athleteKey){

            $causal_payment = 'Pagato';

            $athlete = Athlete::findOrFail($athleteKey);

            $data = collect([]);
            
            // NB: INIZIO CASO PARTICOLARE (ERRATA DOPPIA ISCRIZIONE CON DOPPIA REGISTRAZIONE PAGAMENTO)
            // Potrebbe verificarsi il caso che viene registrata erroneamente un'iscrizione due volte
            // ed è stato registrato un pagamento anche della seconda iscrizione errata.
            // a quel punto rimuovo la seconda iscrizione errata e rimuovo anche il relativo pagamento correttivo
            $exists = collect([]);
            $amount_to_remove = 0;
            
            $rows->each(function($item) use($exists, $data, &$amount_to_remove, $causal_payment){
                if($item['causal'] == $causal_payment){
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
                $data = $data->map(function($item) use(&$amount_to_remove, $causal_payment){
                    if($item['causal'] == $causal_payment && $amount_to_remove && $item['causal'] >= $amount_to_remove){
                        $item['amount'] = $item['amount'] + $amount_to_remove;
                        $amount_to_remove = 0;
                    }
                    return $item;
                });
            };
            // FINE CASO PARTICOLARE (ERRATA DOPPIA ISCRIZIONE CON DOPPIA REGISTRAZIONE PAGAMENTO)

            //3 - DEFINISCO IL BUDGET DELL'ATLETA
            $budget = abs($data->filter(function($item) use($causal_payment){
                return $item['causal'] == $causal_payment;
            })->reduce(function($amount, $item){
                $amount = $amount + $item['amount'];
                return $amount;
            }, 0));


            //4 - DEFINISCO LE ISCRIZIONI DELL'ATLETA
            $fees_to_pay = $data->filter(function($item) use($causal_payment){
                return $item['causal'] != $causal_payment;
            });
            
            //5 - CICLO LE ISCRIZIONI E REGISTRO I PAGAMENTI
            $last_fees_to_pay_key = $fees_to_pay->keys()->last();
            $fees_to_pay->each(function($item, $keyRow) use($athlete, &$budget, $last_fees_to_pay_key){
                
                $race_name = (explode(' - ', trim($item['causal'])))[0];
                $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();
                
                if($item['amount'] < 0){
                    abort(500, "{$athlete->id} | Qualcosa non va, non può essere che la causale sia diversa da pagato e ci sia un importo negativo");
                }

                if($budget >= $item['amount']){
                    // se ho budget per pagare iscrivo alla gara e la segno come pagata
                    $athlete->fees()->syncWithoutDetaching(
                        [
                            $fee->id => [
                                'custom_amount' => $item['amount'],
                                'created_at' => $item['date'],
                                'payed_at' => Carbon::now()
                            ]
                        ]
                    );

                    // sottraggo l'importo dal budget disponibile
                    $budget = ($budget - $item['amount']);
                }else{
                    
                    // se non ho budget a sufficienza per pagare iscrivo alla gara, 
                    // se sono sull'ultima riga e ho del budget inoltre lo scalo all'importo da pagare e imposto il budegt a zero
                    
                    $amount = $item['amount'];
                    if($last_fees_to_pay_key == $keyRow){
                        $amount = ($amount - $budget);
                        $budget = 0;
                    }

                    $athlete->fees()->syncWithoutDetaching(
                        [
                            $fee->id => [
                                'custom_amount' => $amount,
                                'created_at' => $item['date'],
                                'payed_at' => !$amount ? Carbon::now() : null // segno la gara come pagata solo se l'importo è zero
                            ]
                        ]
                    );
                }
            });

            //6 - SE DOPO AVER REGISTRATO TUTTI I PAGAMENTI MI RIMANE DEL BUDGET EMETTO UN VOUCHER
            if($budget > 0){
                $athlete->vouchers()->create([
                    'name' => 'Buono gara',
                    'type' => VoucherType::Credit,
                    'amount' => $budget
                ]);
            }else if($budget < 0){
                abort(500, "{$athlete->id} | Qualcosa non va, Il budget non può essere negativo");
            }
        });
    }
}
