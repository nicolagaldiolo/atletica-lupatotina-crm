<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SaleRequest;
use App\Models\Article;
use App\Models\Athlete;
use App\Models\Order;
use App\Models\OrderRow;
use App\Models\Season;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

            $builder = $order->rows()->with(['article.imageDefault', 'transaction.cashed']);

            return datatables()->eloquent($builder)->make(true);
        }else{

            $accountants = User::HandlePaymentsRace()->get();
            return view('backend.seasons.orders.rows.index', compact('season', 'order', 'accountants'));
        }
    }

    public function update(SaleRequest $request, Season $season, Order $order)
    {

        $order->rows()->whereIn('id', $request->get('ids', []))->get()->each(function($orderRow) use($request){    
            $orderRow->update($request->only('status'));
            
            if($request->has('payed')){
                $payed = (bool) $request->get('payed', false);
                $bank_transfer = (bool) $request->get('bank_transfer', false);
                $cashed_by = $request->get('cashed_by', null);
                handleTransaction($orderRow, $payed, $orderRow->total_amount, $bank_transfer, $cashed_by);
            }
        });

        return response()->json();
    }

    public function products(Season $season)
    {

        if (request()->ajax()) {
            $builder = $season->orderRows()
                ->select('article_images.image', 'articles.name', 'order_rows.article_id', 'order_rows.variant', DB::raw('SUM(order_rows.quantity) as quantity'))
                ->groupBy('article_images.image')
                ->groupBy('articles.name')
                ->groupBy('orders.season_id')
                ->groupBy('order_rows.article_id')
                ->groupBy('order_rows.variant')
                ->leftJoinRelationship('article.imageDefault');

            return datatables()->eloquent($builder)
                ->editColumn('image', function ($data) {
                
                    $path = null;
                    if($data->image && Storage::exists($data->image)){
                        $path = asset('storage/' . $data->image);
                    }

                    return $path;
                })->make(true);
        }
    }
}
