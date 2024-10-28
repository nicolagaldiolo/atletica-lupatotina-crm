<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentsRequest;
use App\Models\Athlete;
use App\Models\AthleteFee;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('registerPayment', AthleteFee::class);

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
    public function store(PaymentsRequest $request)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        foreach($request->get('payments' , []) as $key=>$value){
            Athlete::findOrFail($key)->fees()->syncWithPivotValues(array_keys($value), ['payed_at' => Carbon::now()], false);
        }
        Utility::flashMessage();
        return redirect(route('payments.create'));
    }
}