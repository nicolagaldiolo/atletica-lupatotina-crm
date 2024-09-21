<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeesRequest;
use App\Models\Athlete;
use App\Models\Fee;
use App\Models\Race;

class RaceFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Race $race)
    {
        $this->authorize('xxx');

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
    public function create(Race $race)
    {
        $this->authorize('xxx');

        $this->authorize('create', Fee::class);

        $fee = new Fee();
        return view('backend.races.fees.create', compact('race', 'fee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeesRequest $request, Race $race)
    {
        $this->authorize('xxx');

        $this->authorize('create', Fee::class);
        $race->fees()->create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', $race));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('xxx');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Race $race, Fee $fee)
    {
        $this->authorize('xxx');

        $this->authorize('update', $fee);
        return view('backend.races.fees.edit', compact('race', 'fee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeesRequest $request, Race $race, Fee $fee)
    {
        $this->authorize('xxx');

        $this->authorize('update', $fee);
        $fee->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.index', $race));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Race $race, Fee $fee)
    {
        $this->authorize('xxx');

        $this->authorize('delete', $fee);
    }

    public function athletesSubscribeable(Race $race, Fee $fee)
    {
        $this->authorize('xxx');
        
        if (request()->ajax()) {
            $athletes = Athlete::with(['validVouchers'])->whereDoesntHave('fees', function($query) use($fee){
                $query->where('fees.id', $fee->id);
            })->get();
            
            return view('backend.races.subscriptions.partials.athletes_subscribeable', compact('athletes', 'fee'));
        }
    }
}
