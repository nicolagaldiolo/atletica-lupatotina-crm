<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
        $athletes = Athlete::whereHas('fees', function($query){
            $query->whereNull('payed_at');
        })->with([
            'fees' => function($query){
                $query->whereNull('payed_at');
            },
            'fees.race'
        ])->get();

        return view('backend.payments.create', compact('athletes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach($request->get('payments') as $key=>$value){
            Athlete::findOrFail($key)->fees()->syncWithPivotValues(array_keys($value), ['payed_at' => Carbon::now()], false);
        }
        Utility::flashMessage();
        return redirect(route('payments.create'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}