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
    public function index($raceType)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        $accounts = User::whereHas('proceeds.fee.race', function($query) use($raceType){
            $query->type($raceType);
        })->get();
        
        $proceedRangePeriod = $this->getProceedRangePeriod();
        $currentPeriod = $proceedRangePeriod['current_period'];
        $yearForExport = $proceedRangePeriod['year_for_export'];
        
        return view('backend.proceeds.index', compact('proceedRangePeriod', 'accounts', 'yearForExport', 'currentPeriod', 'raceType'));
    }

    /**
     * Display the specified resource.
     */
    public function show($raceType, $user)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        if (request()->ajax()) {
            $user = ($user = intval($user)) ? User::whereHas('proceeds')->findOrFail($user) : null; 
            $builder = ($user ? $user->proceeds() : Proceed::byBankTransfer())->toDeduct()->whereHas('fee.race', function($query) use($raceType){
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
            $user = ($user = intval($user)) ? User::whereHas('proceeds')->findOrFail($user) : null; 
            $builder = ($user ? $user->proceeds() : Proceed::byBankTransfer())->deducted()->whereHas('fee.race', function($query) use($raceType){
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
    public function update(Request $request, $raceType, $user)
    {
        $this->authorize('deductPayment', AthleteFee::class);
        
        if (request()->ajax()) {
            $user = ($user = intval($user)) ? User::whereHas('proceeds')->findOrFail($user) : null; 
            $available_ids = ($user ? $user->proceeds() : Proceed::byBankTransfer())->whereHas('fee.race', function($query) use($raceType){
                $query->type($raceType);
            })->toDeduct()->pluck('id')->toArray();

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
            
            ($user ? $user->proceeds() : Proceed::byBankTransfer())->toDeduct()->whereHas('fee.race', function($query) use($raceType){
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
        $all_proceed = Proceed::toDeduct()->orderBy('payed_at', 'asc')->get();
        
        $year_for_export = collect([
            ...Proceed::deducted()->select('deduct_at')->groupBy('deduct_at')->get()->map(function($item){
                return $item->deduct_at->format('Y');
            }),
            ...Proceed::toDeduct()->select('payed_at')->groupBy('payed_at')->get()->map(function($item){
                return $item->payed_at->format('Y');
            })
        ])->unique()->sort()->values();

        $startRange = $all_proceed->first()->payed_at->startOfMonth();
        $endRange = $all_proceed->last()->payed_at->endOfMonth();

        $data = [
            'start_range' => $startRange,
            'end_range' => $endRange,
            'current_period' => Carbon::now(),
            'periods' => CarbonPeriod::create($startRange->format('Y-m-d'), '1 month', $endRange->format('Y-m-d')),
            'year_for_export' => $year_for_export
        ];

        return $data;
    }

    public function export(Request $request)
    {
        $this->authorize('deductPayment', AthleteFee::class);

        $proceedRangePeriod = $this->getProceedRangePeriod();
        $this->validate($request, [
            'year' => [
                'required', 
                Rule::in($proceedRangePeriod['year_for_export']->toArray())
            ]
        ]);
        
        $year_to_export = $request->get('year');

        $accounts = Proceed::deducible()->where(function($query) use($year_to_export){
            $query->where(function($q) use($year_to_export){
                $q->toDeduct()->whereRaw("YEAR(payed_at) = {$year_to_export}");
            })->orWhere(function($q) use($year_to_export){
                $q->deducted()->whereRaw("YEAR(deduct_at) = {$year_to_export}");
            });
        })->with(['cashed', 'athlete', 'fee.race'])->orderBy('bank_transfer')->orderBy('cashed_by')->orderByRaw('athlete_id')->get()->reduce(function($arr, $item){
            $key = $item->cashed->name ?? 'bonifico';

            $proceed_key = $item->deduct_at ? $item->deduct_at->startOfMonth()->format('Y-m') : '0000-00';

            $arr[$key][$proceed_key][] = $item;
            return $arr;
        }, []);

        $filename = Str::slug("Atletica lupatotina incassi {$year_to_export}") . ".xlsx";
        return Excel::download(new ProceedExport($accounts), $filename);
    }

}
