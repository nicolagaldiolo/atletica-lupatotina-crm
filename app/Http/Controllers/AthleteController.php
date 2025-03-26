<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\User;
use App\Models\Athlete;
use App\Classes\Utility;
use App\Enums\RaceType;
use App\Models\AthleteFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\AthletesRequest;
use Illuminate\Support\Facades\Session;

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

        $activeFilterDefault = 1;

        if (request()->ajax()) {

            $builder = Athlete::query()
                ->withCount('fees')
                ->withCount('feesToPay')
                ->with(['certificate', 'feesToPay', 'user'])
                ->leftJoinRelationship('certificate');
                //->leftJoinRelationship('certificate', function(PowerJoinClause $join){
                //    $join->expiring();
                //});

            // Filtro per stato
            Session::put('dataTableSearch.searchByActive', intval(request()->get('searchByActive', $activeFilterDefault)));
            $activeFilter = Session::get('dataTableSearch.searchByActive');
            if ($activeFilter != -1) {
                if($activeFilter == 1){
                    $builder->active();
                }else if($activeFilter == 0){
                    $builder->disabled();
                }
            }

            return datatables()->eloquent($builder)
                ->addColumn('action', function ($athlete) {
                    return view('backend.athletes.partials.action_column', compact('athlete'));
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
                })
                ->orderColumn('certificate', function ($query, $order) {
                    $query->orderBy('certificates.expires_on', $order);
                })->make(true);
        }else{
            $searchByActive = Session::get('dataTableSearch.searchByActive', $activeFilterDefault);

            return view('backend.athletes.index', compact('searchByActive'));
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
        $athlete->is_active = true;
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
    public function show(Athlete $athlete)
    {
        $this->authorize('view', $athlete);
        return view('backend.athletes.show', compact('athlete'));
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

    public function races(Athlete $athlete, $raceType)
    {
        $is_authorized = false;
        if($raceType == RaceType::Race){
            $is_authorized = Gate::any(['subscribeRace', 'registerPaymentRace'], AthleteFee::class);
        }else if($raceType == RaceType::Track){
            $is_authorized = Gate::any(['subscribeTrack', 'registerPaymentTrack'], AthleteFee::class);
        }

        if ($is_authorized || Gate::check('viewAny', [AthleteFee::class, $athlete])) {
            if (request()->ajax()) {
                $builder = AthleteFee::with(['voucher', 'fee.race', 'cashed', 'owner'])
                    ->whereHas('fee.race', function($query) use($raceType){
                        $query->where('type', $raceType);
                    })
                    ->leftJoinRelationship('fee.race')
                    ->where('athlete_id', $athlete->id);
    
                return datatables()->eloquent($builder)
                ->filterColumn('fee', function($query, $keyword) {
                    $query->whereRaw("fees.name like ?", ["%{$keyword}%"]);
                })
                ->orderColumn('fee', function ($query, $order) {
                    $query->orderBy('races.name', $order);
                })
                ->addColumn('action', function ($athleteFee) use($raceType){
                    return view('backend.athletes.fees.partials.action_column', compact('athleteFee', 'raceType'));
                })->make(true);
            }else{
                return view('backend.athletes.fees.index', compact('athlete', 'raceType'));
            }
        }else{
            abort(403);
        }
    }

    public function editFee(Request $request, Athlete $athlete, $raceType, Fee $fee, AthleteFee $athleteFee)
    {
        
        if($raceType == RaceType::Race){
            $this->authorize('registerPaymentRace', $athleteFee);
        }else if($raceType == RaceType::Track){
            $this->authorize('registerPaymentTrack', $athleteFee);
        }else{
            abort(401);
        }
        
        $accountants = User::HandlePaymentsRace()->get();
        $athlete = $athleteFee->athlete;
        $fee->load('race');
        
        return view('backend.athletes.fees.edit', compact('athlete', 'raceType', 'fee', 'athleteFee', 'accountants'));
    }

    public function updateFee(Request $request, Athlete $athlete, $raceType, Fee $fee, AthleteFee $athleteFee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('registerPaymentRace', $athleteFee);
        }else if($raceType == RaceType::Track){
            $this->authorize('registerPaymentTrack', $athleteFee);
        }else{
            abort(401);
        }

        $validated = $request->validate([
            'payed' => 'required|boolean',
            'bank_transfer' => 'required|boolean',
            'cashed_by' => [
                'required',
                Rule::exists('users', 'id'),
            ]
        ]);

        cashFee($athleteFee, $validated);

        Utility::flashMessage();
        return redirect(route('athletes.fees.index', [$athleteFee->athlete, $raceType]));
    }

    public function destroySubscription(Athlete $athlete, $raceType, Fee $fee, AthleteFee $athleteFee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('subscribeRace', $athleteFee);
        }else if($raceType == RaceType::Track){
            $this->authorize('subscribeTrack', $athleteFee);
        }else{
            abort(401);
        }

        $athleteFee->delete();
        Utility::flashMessage();
        return redirect(route('athletes.fees.index', [$athleteFee->athlete, $raceType]));
    }
}
