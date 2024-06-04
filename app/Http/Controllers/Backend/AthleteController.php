<?php

namespace App\Http\Controllers\Backend;

use App\Models\Athlete;
use App\Classes\Utility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AthletesRequest;
use App\Models\AthleteFee;

class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $this->authorize('viewAny', Athlete::class);

        if (request()->ajax()) {
            return datatables()->eloquent(Athlete::query()->withCount('fees')->with(['feesToPay']))->addColumn('action', function ($athlete) {
                return view('backend.athletes.partials.action_column', compact('athlete'));
            })
            ->filterColumn('name', function($query, $keyword) {
                $sql = "CONCAT(name, surname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('surname', $order)->orderBy('name', $order);
            })
            ->editColumn('name', function ($data) {
                return $data->fullname;
            })->addColumn('certificate', function ($athlete) {
                return ""; //$athlete && $athlete->certificate ? $athlete->certificate->expires_on : null;
            })->make(true);
        }else{
            return view('backend.athletes.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Athlete::class);
        $athlete = new Athlete();
        return view('backend.athletes.create', compact('athlete'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AthletesRequest $request)
    {
        $this->authorize('create', Athlete::class);
        $athlete = Athlete::create($request->validated());
        Utility::flashSuccess();
        return redirect(route('backend.athletes.edit', $athlete));
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
    public function edit(Athlete $athlete)
    {
        $this->authorize('update', $athlete);
        return view('backend.athletes.edit', compact('athlete'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AthletesRequest $request, Athlete $athlete)
    {
        $this->authorize('update', $athlete);
        $athlete->update($request->validated());
        Utility::flashSuccess();
        return redirect(route('backend.athletes.edit', $athlete));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Athlete $athlete)
    {
        $this->authorize('delete', $athlete);
        $athlete->delete();
        Utility::flashSuccess();
        return redirect(route('backend.athletes.index'));
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return Response
     */
    public function trashed()
    {
        $this->authorize('restore', Athlete::class);
        if (request()->ajax()) {
            return datatables()->eloquent(Athlete::onlyTrashed())->addColumn('action', function ($athlete) {
                return view('backend.athletes.partials.action_column_trashed', compact('athlete'));
            })
            ->editColumn('name', function ($data) {
                return $data->name . ' ' . $data->surname;
            })->make(true);
        }else{
            return view('backend.athletes.archive');
        }
    }

    public function showTrashed($id)
    {
        $athlete = Athlete::onlyTrashed()->findOrFail($id);
        $this->authorize('view', $athlete);
        return view('backend.athletes.archive_show', compact('athlete'));
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $athlete = Athlete::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $athlete);
        $athlete->restore();
        Utility::flashSuccess();
        return redirect(route('backend.athletes.edit', $athlete));
    }

    public function races(Athlete $athlete)
    {
        if (request()->ajax()) {
            $builder = AthleteFee::with(['fee.race'])->where('athlete_id', $athlete->id);

            return datatables()->eloquent($builder)->make(true);
            //->addColumn('action', function ($athleteFee) use($athlete){
            //    return view('backend.races.athletes.partials.action_column', compact('race', 'athleteFee'));
            //})->make(true);
        }else{
            return view('backend.athletes.races.index', compact('athlete'));
        }
    }

    /*
    public function athletes(Race $race)
    {
        if (request()->ajax()) {
            $builder = AthleteFee::with(['athlete', 'fee'])->whereHas('fee', function($query) use($race){
                $query->where('race_id', $race->id);
            });

            return datatables()->eloquent($builder)
            ->addColumn('action', function ($athleteFee) use($race){
                return view('backend.races.athletes.partials.action_column', compact('race', 'athleteFee'));
            })->make(true);
        }else{
            return view('backend.races.athletes.index', compact('race'));
        }
    }
    */
}
