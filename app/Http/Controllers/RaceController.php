<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Classes\Utility;
use App\Enums\RaceType;
use App\Exports\RaceSubscriptionsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RacesRequest;
use App\Http\Requests\RaceSubscriptionsRequest;
use App\Models\AthleteFee;
use App\Models\Fee;
use App\Models\Race;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class RaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index($raceType)
    {
        if($raceType == RaceType::Race){
            $this->authorize('viewAnyRace', Race::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('viewAnyTrack', Race::class);
        }else{
            abort(401);
        }

        if (request()->ajax()) {

            $builder = Race::type($raceType)->withCount(['fees', 'athleteFee'])->with('fees.athletes');
            
            // Filtro per anno
            Session::put('dataTableSearch.searchByYear', request()->get('searchByYear', now()->year));
            $year = Session::get('dataTableSearch.searchByYear');
            if ($year) {
                $builder->whereRaw("DATE_FORMAT(date, '%Y') = ?", [$year]);
            }

            return datatables()->eloquent($builder)->addColumn('action', function ($race) {
                return view('backend.races.partials.action_column', compact('race'));
            })->make(true);
        }else{

            $searchByYear = Session::get('dataTableSearch.searchByYear', now()->year);
            $years = raceYears();

            return view('backend.races.index', compact('years', 'searchByYear', 'raceType'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($raceType)
    {
        if($raceType == RaceType::Race){
            $this->authorize('createRace', Race::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('createTrack', Race::class);
        }else{
            abort(401);
        }

        $race = new Race();
        $race->type = $raceType;
        return view('backend.races.create', compact('race'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(RacesRequest $request)
    {
        $raceType = $request->get('type');

        if($raceType == RaceType::Race){
            $this->authorize('createRace', Race::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('createTrack', Race::class);
        }else{
            abort(401);
        }
        
        $race = Race::create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.index', $race->type));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('updateRace', $race);
        }else if($raceType == RaceType::Track){
            $this->authorize('updateTrack', $race);
        }else{
            abort(401);
        }

        return view('backend.races.edit', compact('race'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(RacesRequest $request, $raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('updateRace', $race);
        }else if($raceType == RaceType::Track){
            $this->authorize('updateTrack', $race);
        }else{
            abort(401);
        }

        $race->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.index', $raceType));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('deleteRace', $race);
        }else if($raceType == RaceType::Track){
            $this->authorize('deleteTrack', $race);
        }else{
            abort(401);
        }

        $race->delete();
        Utility::flashMessage();
        return redirect(route('races.index', $raceType));
    }

    public function subscriptionCreate($raceType)
    {
        if($raceType == RaceType::Race){
            $this->authorize('subscribeRace', AthleteFee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('subscribeTrack', AthleteFee::class);
        }else{
            abort(401);
        }

        $races = Race::subscribeable()->type($raceType)->whereHas('fees')->with('fees')->get();

        return view('backend.races.subscriptions.create', compact('raceType', 'races'));
    }

    public function subscriptionStore(RaceSubscriptionsRequest $request, $raceType)
    {

        if($raceType == RaceType::Race){
            $this->authorize('subscribeRace', AthleteFee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('subscribeTrack', AthleteFee::class);
        }else{
            abort(401);
        }

        $fee = Fee::with('race')->findOrFail($request->get('fee_id'));
        $fee->athletes()->syncWithoutDetaching($request->get('athletes', []));
        
        Utility::flashMessage();
        return redirect(route('races.subscription.create', $fee->race->type));
    }

    public function athletes($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('reportRace', $race);
        }else if($raceType == RaceType::Track){
            $this->authorize('reportTrack', $race);
        }else{
            abort(401);
        }

        if (request()->ajax()) {
            $builder = AthleteFee::with(['voucher', 'athlete', 'fee'])->whereHas('fee', function($query) use($race){
                $query->where('race_id', $race->id);
            })->leftJoinRelationship('athlete')->leftJoinRelationship('fee');

            return datatables()->eloquent($builder)
                ->orderColumn('athlete', function ($query, $order) {
                    $query->orderBy('athletes.surname', $order)->orderBy('athletes.name', $order);
                })
                ->filterColumn('athlete', function($query, $keyword) {
                    $sql = "CONCAT(athletes.name, athletes.surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('fee', function ($query, $order) {
                    $query->orderBy('fees.name', $order);
                })
                ->filterColumn('fee', function($query, $keyword) {
                    $query->whereRaw('fees.name like ?', ["%{$keyword}%"]);
                })
                ->make(true);
        }else{
            return view('backend.races.athletes.index', compact('race'));
        }
    }

    public function subscriptionsList($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('reportRace', $race);
        }else if($raceType == RaceType::Track){
            $this->authorize('reportTrack', $race);
        }else{
            abort(401);
        }

        $race->load([
            'athleteFee' => [
                'athlete',
                'fee',
                'voucher'
            ],
        ]);

        $filename = Str::slug("Iscrizione {$race->name}") . ".xlsx";
        return Excel::download(new RaceSubscriptionsExport($race->athleteFee), $filename);
    }
}
