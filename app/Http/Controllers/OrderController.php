<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Article;
use App\Models\Order;
use App\Models\Season;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Season $season)
    {


    $builder = $season->orderRows()->select('articles.name', 'order_rows.article_id', 'order_rows.variant', DB::raw('SUM(order_rows.quantity) as quantity'))
                ->groupBy('articles.name')->groupBy('orders.season_id')->groupBy('order_rows.article_id')->groupBy('order_rows.variant')->leftJoinRelationship('article')->get();

        //dd($builder);

        if (request()->ajax()) {

            $builder = $season->orders()->with('athlete')->withCount('rows')->leftJoinRelationship('athlete');

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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Season $season)
    {
        //$this->authorize('create', Article::class);
        $order = new Order();
        
        $articles = Article::active()->get();
        return view('backend.seasons.orders.create', compact('season', 'order', 'articles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request, Season $season)
    {
        //$this->authorize('create', Article::class);

        $order = $season->orders()->create([
            'athlete_id' => auth()->user()->athlete->id
        ]);

        $articles = $request->get('articles' , []);

        $this->handleOrderRows($order, $articles);

        Utility::flashMessage();

        return redirect(route('seasons.orders.index', $season));
    }

    /**
     * Display the specified resource.
     */
    public function show(Season $season, Order $order)
    {
        //$this->authorize('update', $article);

        $order->load('rows');

        return view('backend.seasons.orders.show', compact('season', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Season $season, Order $order)
    {
        //$this->authorize('update', $article);

        $order->load('rows');

        $articles = Article::active()->get();

        return view('backend.seasons.orders.edit', compact('season', 'order', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Season $season, Order $order)
    {
        //$this->authorize('update', $article);
        
        $articles = $request->get('articles' , []);

        $this->handleOrderRows($order, $articles);

        Utility::flashMessage();

        return redirect(route('seasons.orders.index', $season));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season, Order $order)
    {
        //$this->authorize('delete', $article);
        
        $order->delete();

        Utility::flashMessage();
        
        return redirect(route('seasons.orders.index', $season));
    }

    protected function handleOrderRows(Order $order, array $articles)
    {
        // elimino eventuali righe esistenti
        $order->rows->each(function($item){
            $item->forceDelete();
        });

        $i = 10;
    
        collect($articles)->filter(function($item){
            return intval($item['selected']);
        })->each(function($item, $key) use($order){

            $article = Article::active()->where('id', $key)->firstOrFail();

            collect($item['variants'] ?? [])->filter(function($i){
                return intval($i) > 0;
            })->each(function($quantity, $variant_key) use($order, $article){

                $order->rows()->create([
                    'article_id' => $article->id,
                    'amount' => $article->price,
                    'quantity' => $quantity,
                    'variant' => $article->type === ArticleType::Variants ? $variant_key : null,
                    'total_amount' => $article->price * $quantity
                ]);
            });
        });

        $order->update([
            'total_amount' => $order->rows()->sum('total_amount'),
            'quantity' => $order->rows()->sum('quantity'),
        ]);
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
