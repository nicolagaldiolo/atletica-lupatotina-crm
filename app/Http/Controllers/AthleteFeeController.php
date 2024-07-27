<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Models\AthleteFee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AthleteFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AthleteFee $athleteFee)
    {
        $athleteFee->update([
            'payed_at' => ($athleteFee->payed_at ? null : Carbon::now())
        ]);
        Utility::flashMessage();
        return redirect(route('races.athletes', $athleteFee->fee->race));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AthleteFee $athleteFee)
    {
        $athleteFee->load('fee.race');
        $race = $athleteFee->fee->race;
        $athleteFee->delete();
        Utility::flashMessage();
        return redirect(route('races.athletes', $race));
    }
}
