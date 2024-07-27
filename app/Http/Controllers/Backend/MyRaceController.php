<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Race;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyRaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->eloquent(Fee::whereHas('athletes', function(Builder $query){
                $query->where('athlete_id', Auth::user()->athlete->id ?? null);
            })->with('race', 'athletes'))
            ->addColumn('payed_at', function ($fee){
                return $fee->athletes->first()->athletefee->payed_at;
            })->make(true);
        }else{
            return view('myraces.index');
        }
    }
}
