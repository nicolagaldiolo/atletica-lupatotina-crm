<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Article::class);

        if (request()->ajax()) {

            $builder = Article::query();

            return datatables()->eloquent($builder)
                ->addColumn('action', function ($article) {
                    return view('backend.articles.partials.action_column', compact('article'));
                })->make(true);
        }else{
            return view('backend.articles.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Article::class);
        $article = new Article();
        $article->is_active = true;
        return view('backend.articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
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
