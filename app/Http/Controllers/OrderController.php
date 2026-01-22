<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Article;
use App\Models\Order;
use App\Models\Season;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Season $season)
    {
        if (request()->ajax()) {
            return datatables()->eloquent($season->orders())
                ->addColumn('action', function ($season) {
                    return view('backend.seasons.orders.partials.action_column', compact('season'));
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
    public function store(OrderRequest $request)
    {
        $this->authorize('create', Article::class);
        Article::create($request->validated());
        Utility::flashMessage();
        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $this->authorize('update', $article);
        return view('backend.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);
        $article->update($request->validated());
        Utility::flashMessage();
        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        Utility::flashMessage();
        return redirect(route('articles.index'));
    }
}
