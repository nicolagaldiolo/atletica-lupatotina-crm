<?php

namespace App\Http\Controllers;

use App\Models\AthleteFee;
use App\Models\Proceed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProceedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('registerPayment', AthleteFee::class);

        $accounts = User::whereHas('proceeds')->get();
        return view('backend.proceeds.index', compact('accounts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user = null)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->toDeduct()
                ->with(['athlete', 'fee.race'])
                ->leftJoinRelationship('athlete');

            return datatables()->eloquent($builder)
            ->filterColumn('name', function($query, $keyword) {
                $sql = "CONCAT(name, surname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('athletes.surname', $order)->orderBy('athletes.name', $order);
            })
            ->editColumn('name', function ($data) {
                return $data->athlete->fullname;
            })->make(true);
        }
    }

    public function deducted(User $user)
    {
        $this->authorize('registerPayment', AthleteFee::class);

        if (request()->ajax()) {
            $builder = $user->proceeds()->deducted()->selectRaw('DATE_FORMAT(deduct_at, "%Y-%m") as deduct_at, sum(custom_amount) as amount')
                ->groupByRaw('DATE_FORMAT(deduct_at, "%Y-%m")');

            return datatables()->eloquent($builder)->make(true);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (request()->ajax()) {

            $this->authorize('deductPayment', AthleteFee::class);

            $available_ids = $user->proceeds()->toDeduct()->pluck('id')->toArray();

            $this->validate($request, [
                'ids' => 'required|array',
                'ids.*' => ['required', Rule::exists('athlete_fee', 'id'), Rule::in($available_ids)]
            ]);

            $user->proceeds()->toDeduct()->whereIn('id', $request->get('ids'))->get()->each(function($proced){
                $proced->update([
                    'deduct_at' => Carbon::now()
                ]);
            });
            
            return response(['type' => 'success', 'message' => __('Operazione eseguita con successo')]);        
        }
    }

}
