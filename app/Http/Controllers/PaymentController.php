<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Athlete;
use App\Classes\Utility;
use App\Models\AthleteFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create($raceType)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        $athletes = Athlete::whereHas('fees', function($query) use($raceType){
            $query->whereNull('payed_at')->whereHas('race', function($query) use($raceType){
                $query->type($raceType);
            });
        })->with([
            'fees' => function($query) use($raceType){
                $query->whereNull('payed_at')->whereHas('race', function($query) use($raceType){
                    $query->type($raceType);
                });
            },
            'fees.race' => function($query) use($raceType){
                $query->type($raceType);
            },
            'fees.athletefee',
        ])->get();

        $accountants = User::HandlePayments()->get();

        return view('backend.payments.create', compact('athletes', 'accountants', 'raceType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $raceType)
    {
        $this->authorize('registerPaymentRace', AthleteFee::class);

        $validated = $request->validate([
            'payments' => 'required|array',
            'payments.*' => 'required|array',
            'payments.*.payed' => 'required|boolean',
            'payments.*.bank_transfer' => 'required|boolean',
            'payments.*.cashed_by' => [
                'required',
                Rule::exists('users', 'id')
            ]
        ]);
        
        collect($request->get('payments' , []))->filter(function($item){
            return intval($item['payed']);
        })->each(function($item, $key){
            $athleteFee = AthleteFee::findOrFail($key);
            cashFee($athleteFee, $item);
        });            

        Utility::flashMessage();
        return redirect(route('payments.create', $raceType));
    }
}