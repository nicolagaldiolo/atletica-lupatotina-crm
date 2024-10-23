<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Athlete;
use App\Classes\Utility;
use App\Models\AthleteFee;
use Illuminate\Http\Request;
use App\Exports\AtheletsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\AthletesRequest;
use App\Models\Race;
use Illuminate\Support\Facades\Gate;

class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $this->authorize('viewAny', Athlete::class);

        if (request()->ajax()) {
            return datatables()->eloquent(Athlete::query()->withCount('vouchers')->withCount('fees')->with(['certificate', 'feesToPay', 'user']))
                ->addColumn('action', function ($athlete) {
                    return view('backend.athletes.partials.action_column', compact('athlete'));
                })
                ->orderColumn('certificate', function ($query, $order) {
                    $query->orderBy('certificate.expires_on', $order);
                })
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "CONCAT(name, surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('surname', $order)->orderBy('name', $order);
                })
                ->editColumn('name', function ($data) {
                    return $data->fullname;
                })->make(true);
        }else{
            return view('backend.athletes.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Athlete::class);
        $athlete = new Athlete();
        return view('backend.athletes.create', compact('athlete'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AthletesRequest $request)
    {
        $this->authorize('create', Athlete::class);
        Athlete::create($request->validated());
        Utility::flashMessage();
        return redirect(route('athletes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Athlete $athlete)
    {
        $this->authorize('update', $athlete);
        return view('backend.athletes.edit', compact('athlete'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AthletesRequest $request, Athlete $athlete)
    {
        $this->authorize('update', $athlete);
        $athlete->update($request->validated());
        Utility::flashMessage();
        return redirect(route('athletes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Athlete $athlete)
    {
        $this->authorize('delete', $athlete);
        $athlete->delete();
        Utility::flashMessage();
        return redirect(route('athletes.index'));
    }

    public function races(Athlete $athlete)
    {
        if (!Gate::any(['subscribe', 'registerPayment'], Race::class)) {
            abort(403);
        }

        if (request()->ajax()) {
            $builder = AthleteFee::with(['voucher', 'fee.race'])->where('athlete_id', $athlete->id);
            return datatables()->eloquent($builder)
            ->addColumn('action', function ($athleteFee){
                return view('backend.athletes.races.partials.action_column', compact('athleteFee'));
            })->make(true);
        }else{
            return view('backend.athletes.races.index', compact('athlete'));
        }
    }

    public function payFee(Request $request, AthleteFee $athleteFee)
    {
        $this->authorize('registerPayment', $athleteFee->fee->race);
        
        $athleteFee->update([
            'payed_at' => ($athleteFee->payed_at ? null : Carbon::now())
        ]);
        Utility::flashMessage();
        return redirect(route('athletes.races.index', $athleteFee->athlete));
    }

    public function destroySubscription(AthleteFee $athleteFee)
    {
        $this->authorize('subscribe', $athleteFee->fee->race);

        $athleteFee->delete();
        Utility::flashMessage();
        return redirect(route('athletes.races.index', $athleteFee->athlete));
    }

    public function report()
    {
        $this->authorize('report', Athlete::class);
        
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
