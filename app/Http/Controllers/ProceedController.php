<?php

namespace App\Http\Controllers;

use App\Models\AthleteFee;
use App\Models\Proceed;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProceedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($raceType)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        $accounts = User::whereHas('proceeds.fee.race', function($query) use($raceType){
            $query->type($raceType);
        })->get();
        
        $proceedRangePeriod = $this->getProceedRangePeriod();
        $periods = $proceedRangePeriod['periods'];
        $currentPeriod = $proceedRangePeriod['current_period'];
        
        return view('backend.proceeds.index', compact('proceedRangePeriod', 'accounts', 'raceType'));
    }

    /**
     * Display the specified resource.
     */
    public function show($raceType, User $user = null)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->toDeduct()
                ->whereHas('fee.race', function($query) use($raceType){
                    $query->type($raceType);
                })
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

    public function deducted($raceType, User $user)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->deducted()->whereHas('fee.race', function($query) use($raceType){
                $query->type($raceType);
            })->selectRaw('DATE_FORMAT(deduct_at, "%Y-%m") as deduct_at, sum(custom_amount) as amount')
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
    public function update(Request $request, $raceType, User $user)
    {
        $this->authorize('deductPayment', AthleteFee::class);
        
        if (request()->ajax()) {

            $available_ids = $user->proceeds()->toDeduct()->whereHas('fee.race', function($query) use($raceType){
                $query->type($raceType);
            })->pluck('id')->toArray();
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

            $user->proceeds()->toDeduct()->whereHas('fee.race', function($query) use($raceType){
                $query->type($raceType);
            })->whereIn('id', $request->get('ids'))->get()->each(function($proced) use($request){
                $proced->update([
                    'deduct_at' => $request->get('period')
                ]);
            });
            
            return response(['type' => 'success', 'message' => __('Operazione eseguita con successo')]);        
        }
    }

    protected function getProceedRangePeriod()
    {
        $startRange = Carbon::now()->subMonths(3)->startOfMonth();
        $endRange = Carbon::now()->endOfMonth();
        
        return [
            'start_range' => Carbon::now()->subMonths(3)->startOfMonth(),
            'end_range' => Carbon::now()->endOfMonth(),
            'current_period' => Carbon::now(),
            'periods' => CarbonPeriod::create($startRange->format('Y-m-d'), '1 month', $endRange->format('Y-m-d'))
        ];
    }

}
