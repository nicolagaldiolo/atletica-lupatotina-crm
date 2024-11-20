<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentsRequest;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\User;
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

        $accountants = User::permission(Permissions::HandlePayments)->get();

        return view('backend.payments.create', compact('athletes', 'accountants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentsRequest $request)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        foreach($request->get('payments' , []) as $athlete_id=>$fees){

            $fees_to_mark_as_payed = collect($fees)->filter(function($item){
                return intval($item['payed']);
            })->map(function($item){
                unset($item['payed']);
                $item['payed_at'] = Carbon::now();
                if(intval($item['bank_transfer'])){
                    unset($item['cashed_by']);
                }
                return $item;
            });

            if($fees_to_mark_as_payed->count()){
                Athlete::findOrFail($athlete_id)->fees()->syncWithoutDetaching($fees_to_mark_as_payed);
            }
            
        }
        Utility::flashMessage();
        return redirect(route('payments.create'));
    }
}