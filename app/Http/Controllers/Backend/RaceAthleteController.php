<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\AthletesRacesRequest;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\AthleteRace;
use App\Models\Fee;
use App\Models\Race;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RaceAthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Race $race)
    {
        $this->authorize('viewAny', AthleteRace::class);

        if (request()->ajax()) {

            $builder = AthleteFee::with(['athlete', 'fee'])->whereHas('fee', function($query) use($race){
                $query->where('race_id', $race->id);
            });

            return datatables()->eloquent($builder)
            //->editColumn('name', function ($data){
            //    return $data->fullname;
            //})
            ->addColumn('fee', function ($data){
                return $data->fee->name . ' (' . $data->fee->amount . ')';
            })
            ->addColumn('athlete', function ($data){
                return $data->athlete->fullname;
            })
            ->addColumn('action', function ($data) use($race){
                $race_id = 1;//$data->race_id;
                $athlete_id = 2;//$data->athlete_id;
                return view('backend.races.athletes.partials.action_column', compact('race_id', 'athlete_id'));
            })->make(true);
        }else{
            return view('backend.races.athletes.index', compact('race'));
        }
    }

    //
    ///**
    // * Show the form for creating a new resource.
    // */
    //public function create(Race $race)
    //{
    //    $this->authorize('create', AthleteRace::class);
//
    //    $race->load('fees');
//
    //    $athletes = Athlete::whereDoesntHave('races', function ($query) use($race){
    //        $query->where('races.id', $race->id);
    //    })->get();
//
    //    $athleterace = new AthleteRace();
//
    //    return view('backend.races.athletes.create', compact('race', 'athletes', 'athleterace'));
    //}
//
    ///**
    // * Store a newly created resource in storage.
    // */
    //public function store(AthletesRacesRequest $request, Race $race)
    //{
    //    $this->authorize('create', AthleteRace::class);
//
    //    $ids = $request->get('athletes');
    //    $data = [
    //        'fee_id' => $request->get('fee_id'),
    //        'subscription_at' => $request->get('subscription_at')
    //    ];
//
    //    $race->athletes()->syncWithPivotValues($ids, $data, false);
    //    Utility::flashSuccess();
    //    return redirect(route('backend.races.athletes.index', $race));
    //}
//
    ///**
    // * Display the specified resource.
    // */
    //public function show(string $id)
    //{
    //    //
    //}
//
    ///**
    // * Show the form for editing the specified resource.
    // */
    //public function edit(Race $race, Athlete $athlete)
    //{
    //    $this->authorize('update', AthleteRace::class);
//
    //    $race->load('fees');
    //    $athleterace = $race->athletes()->findOrFail($athlete->id)->athleterace;
    //    $athletes = collect([]);
//
    //    return view('backend.races.athletes.edit', compact('race', 'athlete', 'athleterace', 'athletes'));
    //}
//
    ///**
    // * Update the specified resource in storage.
    // */
    //public function update(AthletesRacesRequest $request, Race $race, Athlete $athlete)
    //{
    //    $this->authorize('update', AthleteRace::class);
//
    //    $data = [
    //        'fee_id' => $request->get('fee_id'),
    //        'subscription_at' => $request->get('subscription_at')
    //    ];
//
    //    $race->athletes()->syncWithPivotValues([$athlete->id], $data, false);
    //    Utility::flashSuccess();
    //    return redirect(route('backend.races.athletes.index', $race));
    //}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee, Athlete $athlete)
    {
        $i = $fee;
        /*$this->authorize('delete', AthleteRace::class);
        $race->athletes()->detach($athlete->id);
        Utility::flashSuccess();
        return redirect(route('backend.races.athletes.index', $race));
        */
    }
}
