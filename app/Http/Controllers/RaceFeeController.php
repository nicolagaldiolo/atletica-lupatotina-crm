<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
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
        $this->authorize('viewAny', Fee::class);

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
        $this->authorize('create', Fee::class);
        $fee = new Fee();
        return view('backend.races.fees.create', compact('race', 'fee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeesRequest $request, $raceType, Race $race)
    {
        $this->authorize('create', Fee::class);
        $race->fees()->create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$raceType, $race]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($raceType, Race $race, Fee $fee)
    {
        $this->authorize('update', $fee);
        return view('backend.races.fees.edit', compact('race', 'fee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeesRequest $request, $raceType, Race $race, Fee $fee)
    {
        $this->authorize('update', $fee);
        $fee->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$race->type, $race]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($raceType, Race $race, Fee $fee)
    {
        $this->authorize('delete', $fee);
        $fee->delete();
        Utility::flashMessage();
        return redirect(route('races.fees.index', [$race->type, $race]));
    }

    public function athletesSubscribeable($raceType, Race $race, Fee $fee)
    {
        $this->authorize('subscribeRace', AthleteFee::class);
        
        if (request()->ajax()) {
            $athletes = Athlete::active()->with(['validVouchers'])->whereDoesntHave('fees', function($query) use($fee){
                $query->where('fees.id', $fee->id);
            })->get();
            
            return view('backend.races.subscriptions.partials.athletes_subscribeable', compact('athletes', 'fee'));
        }
    }
}
