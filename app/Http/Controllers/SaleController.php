<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Article;
use App\Models\Athlete;
use App\Models\Order;
use App\Models\OrderRow;
use App\Models\Season;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Season $season)
    {

        if (request()->ajax()) {

            $builder = $season->orders()->with('athlete')->leftJoinRelationship('athlete');

            return datatables()->eloquent($builder)
                ->orderColumn('fullname', function ($query, $order) {
                    $query->orderBy('athletes.surname', $order)->orderBy('athletes.name', $order);
                })
                ->filterColumn('fullname', function($query, $keyword) {
                    $sql = "CONCAT(athletes.name, athletes.surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->editColumn('fullname', function (Order $order) {
                    return $order->athlete->fullname;
                })
                ->addColumn('action', function (Order $order) use($season){
                    return view('backend.seasons.orders.partials.action_column', compact('season', 'order'));
                })->make(true);
        }else{
            return view('backend.seasons.orders.index', compact('season'));
        }
    }

    public function edit(Season $season, Order $order)
    {
        if (request()->ajax()) {

            $builder = $order->rows()->with('article');

            return datatables()->eloquent($builder)
                ->addColumn('action', function (OrderRow $orderRow) use($season, $order){
                    return view('backend.seasons.orders.rows.partials.action_column', compact('season', 'order', 'orderRow'));
                })->make(true);
        }else{
            return view('backend.seasons.orders.rows.index', compact('season', 'order'));
        }
    }

    public function products(Season $season)
    {

        if (request()->ajax()) {
            $builder = $season->orderRows()->select('articles.name', 'order_rows.article_id', 'order_rows.variant', DB::raw('SUM(order_rows.quantity) as quantity'))
                ->groupBy('articles.name')->groupBy('orders.season_id')->groupBy('order_rows.article_id')->groupBy('order_rows.variant')->leftJoinRelationship('article');

            return datatables()->eloquent($builder)->make(true);
        }
    }
}
