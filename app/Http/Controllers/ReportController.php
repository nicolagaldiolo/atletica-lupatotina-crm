<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Enums\VoucherType;
use App\Enums\ReportRowType;
use App\Exports\AtheletsExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $this->authorize('report', Athlete::class);

        $years = raceYears();
        $searchByYear = Session::get('reports.searchByYear', now()->year);

        $athletes = Athlete::all();

        return view('backend.reports.index', compact('athletes','years', 'searchByYear'));
    }

    public function download()
    {
        $this->authorize('report', Athlete::class);
        
        /*
        $data = Athlete::where('id', 1)->whereHas('fees.race', function($query){
            $query->whereRaw("DATE_FORMAT(date, '%Y') = ?", ['2024']);
        })->with([
            'fees' => function($query){
                $query->whereHas('race', function($query){
                    $query->whereRaw("DATE_FORMAT(date, '%Y') = ?", ['2024']);
                });
            },
            'fees.race',
            'fees.athletefee.voucher',
            'validVouchers'
        ])->get();

        $i = 10;
        */

        $data = Athlete::whereHas('fees')->with([
            'fees' => [
                'race',
                'athletefee.voucher'
            ],
            'validVouchers'
        ])->get()->reduce(function($arr, $item){

            $item->fees->each(function($fee) use($item, &$arr){
                $arr[$item->id][] = [
                    'athlete_name' => $item->full_name,
                    'type' => ReportRowType::Subscription,
                    'event' => $fee->race->name . ' (' . $fee->name . ')',
                    'event_amount' => $fee->amount,
                    'created_at' => $fee->athletefee->created_at,
                    'voucher' => ($fee->athletefee->voucher && $fee->athletefee->voucher->type == VoucherType::Credit) ? $fee->athletefee->voucher->amount : null,
                    'penalty' => ($fee->athletefee->voucher && $fee->athletefee->voucher->type == VoucherType::Penalty) ? $fee->athletefee->voucher->amount : null,
                    'amount' => $fee->athletefee->custom_amount
                ];

                if($fee->athletefee->payed_at){
                    $arr[$item->id][] = [
                        'athlete_name' => $item->full_name,
                        'type' => ReportRowType::Payment,
                        'event' => null,
                        'event_amount' => null,
                        'created_at' => $fee->athletefee->payed_at,
                        'voucher' => null,
                        'penalty' => null,
                        'amount' => ($fee->athletefee->custom_amount * -1)
                    ];
                }
            });

            $item->validVouchers->each(function($voucher) use($item, &$arr){
                $arr[$item->id][] = [
                    'athlete_name' => $item->full_name,
                    'type' => ReportRowType::Voucher,
                    'event' => null,
                    'event_amount' => null,
                    'created_at' => $voucher->created_at,
                    'voucher' => ($voucher->type == VoucherType::Credit) ? $voucher->amount : null,
                    'penalty' => ($voucher->type == VoucherType::Penalty) ? $voucher->amount : null,
                    'amount' => ($voucher->amount_calculated * -1)
                ];
            });


            return $arr;
        }, []);

        return Excel::download(new AtheletsExport($data), "Situazione Atleti.xlsx");
    }
}
