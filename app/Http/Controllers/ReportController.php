<?php

namespace App\Http\Controllers;

use App\Exports\AtheletsExport;
use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function athletes()
    {
        $data = Athlete::whereHas('fees')->with('fees.race')->get()->reduce(function($arr, $item){
            $item->fees->each(function($fee) use($item, &$arr){
                $baseData = [
                    'athlete_name' => $item->full_name,
                ];
                $arr[] = array_merge($baseData, [
                    'type' => 'subscription',
                    'movement_name' => $fee->race->name . ' - ' . $fee->name,
                    'created_at' => $fee->athletefee->created_at,
                    'amount' => $fee->amount
                ]);

                if($fee->athletefee->payed_at){
                    $arr[] = array_merge($baseData, [
                        'type' => 'payment',
                        'movement_name' => 'Pagato',
                        'created_at' => $fee->athletefee->payed_at,
                        'amount' => ($fee->amount * -1)
                    ]);
                }
            });

            return $arr;
        }, []);

        return Excel::download(new AtheletsExport($data), "Situazione Atleti.xlsx");
    }
}
