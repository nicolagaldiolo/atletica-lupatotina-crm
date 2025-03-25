<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\RaceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeesRequest;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\Fee;
use App\Models\Race;

class RaceFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('viewAnyRace', Fee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('viewAnyTrack', Fee::class);
        }else{
            abort(401);
        }

        if (request()->ajax()) {
            return datatables()->eloquent($race->fees())
            ->addColumn('action', function ($fee) use($race){
                return view('backend.races.fees.partials.action_column', compact('race', 'fee'));
            })
            ->make(true);
        }else{
            return view('backend.races.fees.index', compact('race'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('createRace', Fee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('createTrack', Fee::class);
        }else{
            abort(401);
        }

        $fee = new Fee();
        return view('backend.races.fees.create', compact('race', 'fee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeesRequest $request, $raceType, Race $race)
    {
        if($raceType == RaceType::Race){
            $this->authorize('createRace', Fee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('createTrack', Fee::class);
        }else{
            abort(401);
        }

        $race->fees()->create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$raceType, $race]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($raceType, Race $race, Fee $fee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('updateRace', $fee);
        }else if($raceType == RaceType::Track){
            $this->authorize('updateTrack', $fee);
        }else{
            abort(401);
        }

        return view('backend.races.fees.edit', compact('race', 'fee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeesRequest $request, $raceType, Race $race, Fee $fee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('updateRace', $fee);
        }else if($raceType == RaceType::Track){
            $this->authorize('updateTrack', $fee);
        }else{
            abort(401);
        }
        $fee->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$race->type, $race]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($raceType, Race $race, Fee $fee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('deleteRace', $fee);
        }else if($raceType == RaceType::Track){
            $this->authorize('deleteTrack', $fee);
        }else{
            abort(401);
        }
        
        $fee->delete();
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$race->type, $race]));
    }

    public function athletesSubscribeable($raceType, Race $race, Fee $fee)
    {
        if($raceType == RaceType::Race){
            $this->authorize('subscribeRace', AthleteFee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('subscribeTrack', AthleteFee::class);
        }else{
            abort(401);
        }
        
        if (request()->ajax()) {
            $athletes = Athlete::active()->with(['validVouchers'])->whereDoesntHave('fees', function($query) use($fee){
                $query->where('fees.id', $fee->id);
            })->get();
            
            return view('backend.races.subscriptions.partials.athletes_subscribeable', compact('athletes', 'fee'));
        }
    }
}
