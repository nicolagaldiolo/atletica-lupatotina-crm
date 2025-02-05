<?php

namespace App\Http\Controllers;

use App\Exports\ProceedExport;
use App\Models\AthleteFee;
use App\Models\Proceed;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ProceedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('registerPayment', AthleteFee::class);

        $accounts = User::whereHas('proceeds')->get();
        
        $proceedRangePeriod = $this->getProceedRangePeriod();
        $periods = $proceedRangePeriod['periods'];
        $currentPeriod = $proceedRangePeriod['current_period'];
        
        return view('backend.proceeds.index', compact('proceedRangePeriod', 'accounts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user = null)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->toDeduct()
                ->with(['athlete', 'fee.race'])
                ->leftJoinRelationship('athlete');

            return datatables()->eloquent($builder)
            ->filterColumn('name', function($query, $keyword) {
                $sql = "CONCAT(name, surname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('athletes.surname', $order)->orderBy('athletes.name', $order);
            })
            ->editColumn('name', function ($data) {
                return $data->athlete->fullname;
            })
            //https://yajrabox.com/docs/laravel-datatables/master/response-with
            ->with('total', $builder->sum('custom_amount'))
            ->make(true);
        }
    }

    public function deducted(User $user)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->deducted()->selectRaw('DATE_FORMAT(deduct_at, "%Y-%m") as deduct_at, sum(custom_amount) as amount')
                ->groupByRaw('DATE_FORMAT(deduct_at, "%Y-%m")');

            return datatables()
                ->eloquent($builder)
                ->with('total', $builder->get()->sum('amount'))
                ->make(true);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('deductPayment', AthleteFee::class);
        
        if (request()->ajax()) {

            $available_ids = $user->proceeds()->toDeduct()->pluck('id')->toArray();
            $proceedRangePeriod = $this->getProceedRangePeriod();
            
            $this->validate($request, [
                'ids' => 'required|array',
                'ids.*' => ['required', Rule::exists('athlete_fee', 'id'), Rule::in($available_ids)],
                'period' => [
                    'required', 
                    "after_or_equal:{$proceedRangePeriod['start_range']}", 
                    "before_or_equal:{$proceedRangePeriod['end_range']}"
                ]
            ]);

            $user->proceeds()->toDeduct()->whereIn('id', $request->get('ids'))->get()->each(function($proced) use($request){
                $proced->update([
                    'deduct_at' => $request->get('period')
                ]);
            });
            
            return response(['type' => 'success', 'message' => __('Operazione eseguita con successo')]);        
        }
    }

    protected function getProceedRangePeriod()
    {
        $all_proceed = Proceed::toDeduct()->orderBy('payed_at', 'asc')->get();
        $startRange = $all_proceed->first()->payed_at->startOfMonth();
        $endRange = $all_proceed->last()->payed_at->endOfMonth();
        
        return [
            'start_range' => $startRange,
            'end_range' => $endRange,
            'current_period' => Carbon::now(),
            'periods' => CarbonPeriod::create($startRange->format('Y-m-d'), '1 month', $endRange->format('Y-m-d'))
        ];
    }

    public function export()
    {
        $this->authorize('deductPayment', AthleteFee::class);
        
        $filename = Str::slug("Iscrizione") . ".xlsx";

        $accounts = User::whereHas('proceeds')->with([
            'proceeds' => function($query){
                $query->with(['athlete', 'fee.race'])
                ->orderByRaw('athlete_id');
            }
        ])->get()->reduce(function($arr, $item){
            $arr[$item->name] = $item->proceeds->groupBy(function ($i, int $key) {
                if($i->deduct_at){
                    return $i->deduct_at->startOfMonth()->format('Y-m');
                }else{
                    return '0000-00';
                }
            })->sort();
            
            return $arr;
        }, []);

        return Excel::download(new ProceedExport($accounts), $filename);
    }

}
