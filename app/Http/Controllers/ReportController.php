<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Enums\VoucherType;
use App\Enums\ReportRowType;
use App\Exports\AtheletsExport;
use App\Http\Controllers\Controller;
use App\Models\Race;
use Illuminate\Http\Request;
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

    public function download(Request $request)
    {
        $this->authorize('report', Athlete::class);

        $request->validate([
            'year' => 'required|numeric',
            'race_id' => 'nullable|exists:races,id',
            'athlete_id' => 'nullable|exists:athletes,id',
        ]);
        
        $year = $request->get('year');
        $race_id = $request->get('race_id');
        $athlete_id = $request->get('athlete_id');

        $data = Athlete::when($athlete_id, function($query) use($athlete_id){
            $query->where('id', $athlete_id);
        })->whereHas('fees.race', function($query) use($race_id, $year){
            $query->when($year, function($q) use($year){
                $q->whereRaw("DATE_FORMAT(date, '%Y') = {$year}");
            })->when($race_id, function($q) use($race_id){
                $q->where('id', $race_id);
            });
        })->with([
            'fees' => function($query) use($race_id, $year){
                $query->whereHas('race', function($query) use($race_id, $year){
                    $query->when($year, function($q) use($year){
                        $q->whereRaw("DATE_FORMAT(date, '%Y') = {$year}");
                    })->when($race_id, function($q) use($race_id){
                        $q->where('id', $race_id);
                    });
                });
            },
            'fees.race',
            'fees.athletefee.voucher',
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

    public function races(int $year)
    {
        $this->authorize('report', Athlete::class);

        if (request()->ajax()) {

            Session::put('reports.searchByYear', $year);

            $races = Race::whereRaw("DATE_FORMAT(date, '%Y') = {$year}")->get();
            
            return view('backend.reports.partials.race_list', compact('races'));
        }
    }
}
