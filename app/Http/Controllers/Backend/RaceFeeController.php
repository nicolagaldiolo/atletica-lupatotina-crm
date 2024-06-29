<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeesRequest;
use App\Models\Fee;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Race $race)
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
    public function create(Race $race)
    {
        $this->authorize('create', Fee::class);

        $fee = new Fee();
        return view('backend.races.fees.create', compact('race', 'fee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeesRequest $request, Race $race)
    {
        $this->authorize('create', Fee::class);
        $fee = $race->fees()->create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.edit', [$race, $fee]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Race $race, Fee $fee)
    {
        $this->authorize('update', $fee);
        return view('backend.races.fees.edit', compact('race', 'fee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeesRequest $request, Race $race, Fee $fee)
    {
        $this->authorize('update', $fee);
        $fee->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.fees.edit', [$race, $fee]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Race $race, Fee $fee)
    {
        $this->authorize('delete', $fee);
    }
}
