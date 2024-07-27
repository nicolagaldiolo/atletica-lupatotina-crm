<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Support\Facades\Auth;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {

        $races_to_pay = Auth::user()->athlete->fees->filter(function($item){
            return !$item->athletefee->payed_at;
        });

        $races_payed = Auth::user()->athlete->fees->filter(function($item){
            return $item->athletefee->payed_at;
        });

        $certificate = Auth::user()->athlete->certificate;
        
        return view('backend.index', compact('races_payed', 'races_to_pay', 'certificate'));
    }

    public function certificates()
    {
        if (request()->ajax()) {

            $certificates = Athlete::query()->with(['certificate']);

            /*// filtro sulle categorie
            $category = request()->input('category');
            if ($category) {
                $articles->whereHas('categories', function($q) use($category) {
                    $q->where('pim_categories.id', $category);
                });
            }
                */

            return datatables()->eloquent($certificates)
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "CONCAT(name, surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('surname', $order)->orderBy('name', $order);
                })
                ->editColumn('name', function ($data) {
                    return $data->fullname;
                })->make(true);
                /*
                ->addColumn('action', function ($athlete) {
                    return null;
                    //return view('backend.athletes.partials.action_column', compact('athlete'));
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
                })->make(true);
                */
        }
    }
}
