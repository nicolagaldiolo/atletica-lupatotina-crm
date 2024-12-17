<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Classes\Utility;
use App\Exports\RaceSubscriptionsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RacesRequest;
use App\Http\Requests\RaceSubscriptionsRequest;
use App\Models\AthleteFee;
use App\Models\Fee;
use App\Models\Race;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class RaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $this->authorize('viewAny', Race::class);

        if (request()->ajax()) {

            $builder = Race::query()->withCount(['fees', 'athleteFee'])->with('fees.athletes');
            
            // Filtro per anno
            Session::put('dataTableSearch.searchByYear', request()->get('searchByYear', now()->year));
            $year = Session::get('dataTableSearch.searchByYear');
            if ($year) {
                $builder->whereRaw("DATE_FORMAT(date, '%Y') = ?", [$year]);
            }

            return datatables()->eloquent($builder)->addColumn('action', function ($race) {
                return view('backend.races.partials.action_column', compact('race'));
            })->make(true);
        }else{

            $searchByYear = Session::get('dataTableSearch.searchByYear', now()->year);
            $years = raceYears();

            return view('backend.races.index', compact('years', 'searchByYear'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Race::class);
        $race = new Race();
        return view('backend.races.create', compact('race'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(RacesRequest $request)
    {
        $this->authorize('create', Race::class);
        Race::create($request->validated());
        Utility::flashMessage();
        return redirect(route('races.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Race $race)
    {
        $this->authorize('update', $race);

        return view('backend.races.edit', compact('race'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(RacesRequest $request, Race $race)
    {
        $this->authorize('update', $race);
        $race->update($request->validated());
        Utility::flashMessage();
        return redirect(route('races.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Race $race)
    {
        $this->authorize('delete', $race);
        $race->delete();
        Utility::flashMessage();
        return redirect(route('races.index'));
    }

    public function subscriptionCreate()
    {
        $this->authorize('subscribe', AthleteFee::class);

        $races = Race::subscribeable()->whereHas('fees')->with('fees')->get();

        return view('backend.races.subscriptions.create', compact('races'));
    }

    public function subscriptionStore(RaceSubscriptionsRequest $request)
    {
        $this->authorize('subscribe', AthleteFee::class);
        
        Fee::findOrFail($request->get('fee_id'))->athletes()->syncWithoutDetaching($request->get('athletes', []));
        
        Utility::flashMessage();
        return redirect(route('races.subscription.create'));
    }

    public function athletes(Race $race)
    {
        $this->authorize('report', $race);

        if (request()->ajax()) {
            $builder = AthleteFee::with(['voucher', 'athlete', 'fee'])->whereHas('fee', function($query) use($race){
                $query->where('race_id', $race->id);
            })->leftJoinRelationship('athlete')->leftJoinRelationship('fee');

            return datatables()->eloquent($builder)
                ->orderColumn('athlete', function ($query, $order) {
                    $query->orderBy('athletes.surname', $order)->orderBy('athletes.name', $order);
                })
                ->filterColumn('athlete', function($query, $keyword) {
                    $sql = "CONCAT(athletes.name, athletes.surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('fee', function ($query, $order) {
                    $query->orderBy('fees.name', $order);
                })
                ->filterColumn('fee', function($query, $keyword) {
                    $query->whereRaw('fees.name like ?', ["%{$keyword}%"]);
                })
                ->make(true);
        }else{
            return view('backend.races.athletes.index', compact('race'));
        }
    }

    public function subscriptionsList(Race $race)
    {
        $this->authorize('report', $race);

        $race->load([
            'athleteFee' => [
                'athlete',
                'fee',
                'voucher'
            ],
        ]);

        $filename = Str::slug("Iscrizione {$race->name}") . ".xlsx";
        return Excel::download(new RaceSubscriptionsExport($race->athleteFee), $filename);
    }
}
