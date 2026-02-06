<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Article;
use App\Models\Athlete;
use App\Models\Order;
use App\Models\Season;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Athlete $athlete)
    {

        if (request()->ajax()) {

            $builder = $athlete->orders();

            return datatables()->eloquent($builder)
                ->editColumn('status', function(Order $order){
                    return OrderStatus::getDescription($order->status);
                })
                ->editColumn('payment_status', function(Order $order){
                    return PaymentStatus::getDescription($order->payment_status);
                })
                ->addColumn('action', function (Order $order) use($athlete){
                    return view('backend.athletes.orders.partials.action_column', compact('athlete', 'order'));
                })->make(true);
        }else{
            return view('backend.athletes.orders.index', compact('athlete'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Athlete $athlete)
    {
        //$this->authorize('create', Article::class);
        $order = new Order();
        $articles = Article::active()->get();

        return view('backend.athletes.orders.create', compact('athlete', 'order', 'articles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request, Athlete $athlete)
    {
        //$this->authorize('create', Article::class);

        try{
            $order = $athlete->orders()->create([
                'season_id' => Season::active()->firstOrFail()->id
            ]);

            $articles = $request->get('articles' , []);

            $this->handleOrderRows($order, $articles);

            Utility::flashMessage();
        }catch(Exception $e){
            Utility::flashMessage('error');
        }

        return redirect(route('athletes.orders.index', $athlete));
    }

    /**
     * Display the specified resource.
     */
    public function show(Athlete $athlete, Order $order)
    {
        //$this->authorize('update', $article);

        $order->load('rows');

        return view('backend.athletes.orders.show', compact('athlete', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Athlete $athlete, Order $order)
    {
        //$this->authorize('update', $article);

        $order->load('rows');

        $articles = Article::active()->get();

        return view('backend.athletes.orders.edit', compact('athlete', 'order', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Athlete $athlete, Order $order)
    {
        //$this->authorize('update', $article);
        
        try{
            $articles = $request->get('articles' , []);

            $this->handleOrderRows($order, $articles);

            Utility::flashMessage();
        }catch(Exception $e){
            Utility::flashMessage('error');
        }

        return redirect(route('athletes.orders.index', $athlete));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Athlete $athlete, Order $order)
    {
        //$this->authorize('delete', $article);
        
        $order->delete();

        Utility::flashMessage();
        
        return redirect(route('athletes.orders.index', $athlete));
    }

    protected function handleOrderRows(Order $order, array $articles)
    {
        // elimino eventuali righe esistenti
        $order->rows->each(function($item){
            $item->forceDelete();
        });
    
        collect($articles)->filter(function($item){
            return intval($item['selected']);
        })->each(function($item, $key) use($order){

            $article = Article::active()->where('id', $key)->firstOrFail();

            collect($item['variants'] ?? [])->filter(function($i){
                return intval($i) > 0;
            })->each(function($quantity, $variant_key) use($order, $article){

                for ($i = 1; $i <= $quantity; $i++) {

                    $quantity_unit = 1;

                    $order->rows()->create([
                        'article_id' => $article->id,
                        'amount' => $article->price,
                        'quantity' => $quantity_unit,
                        'variant' => $article->type === ArticleType::Variants ? $variant_key : null,
                        'total_amount' => $article->price * $quantity_unit
                    ]);
                }
            });
        });

        $order->update([
            'total_amount' => $order->rows()->sum('total_amount'),
            'quantity' => $order->rows()->sum('quantity'),
        ]);
    }
}
