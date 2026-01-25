<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\SeasonRequest;
use App\Models\Article;
use App\Models\Order;
use App\Models\OrderRow;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        if (request()->ajax()) {

            $builder = Season::withCount('orders');

            // Filtro per anno
            Session::put('dataTableSearch.searchByYear', request()->get('searchByYear', now()->year));
            $year = Session::get('dataTableSearch.searchByYear');
            if ($year) {
                $builder->whereRaw("DATE_FORMAT(start_at, '%Y') = ?", [$year]);
            }

            return datatables()->eloquent($builder)
                ->addColumn('action', function ($season) {
                    return view('backend.seasons.partials.action_column', compact('season'));
                })->make(true);
        }else{
            $searchByYear = Session::get('dataTableSearch.searchByYear', now()->year);
            $years = seasonYears();

            return view('backend.seasons.index', compact('years', 'searchByYear'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$this->authorize('create', Article::class);
        $season = new Season();
        return view('backend.seasons.create', compact('season'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeasonRequest $request)
    {
        //$this->authorize('create', Article::class);
        Season::create($request->validated());
        Utility::flashMessage();
        
        return redirect(route('seasons.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Season $season)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Season $season)
    {
        //$this->authorize('update', $article);
        return view('backend.seasons.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeasonRequest $request, Season $season)
    {
        //$this->authorize('update', $season);
        $season->update($request->validated());

        Utility::flashMessage();

        return redirect(route('seasons.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        //$this->authorize('delete', $article);
        $season->delete();
        Utility::flashMessage();

        return redirect(route('seasons.index'));
    }
}
