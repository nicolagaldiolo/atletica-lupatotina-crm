<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleImage;
use BenSampo\Enum\Rules\EnumValue;
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

            $builder = Article::with('imageDefault');

            return datatables()->eloquent($builder)
                ->editColumn('type_description', function(Article $article){
                    return ArticleType::getDescription($article->type);
                })
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
    public function create(String $type)
    {
        if (!in_array($type, ArticleType::asArray())) {
            abort(404);
        }

        $this->authorize('create', Article::class);

        $article = new Article();
        $article->is_active = true;
        $article->type = $type;
        
        return view('backend.articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $this->authorize('create', Article::class);

        $article = Article::create($request->validated());

        $this->synRelations($request, $article);
        
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

        $this->synRelations($request, $article);
        
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

    /**
     * Remove the specified resource from storage.
     * @param Article $article
     * @return Renderable
     */
    public function destroyImage(Article $article, ArticleImage $articleImage)
    {
        $this->authorize('update', $article);

        $articleImage->delete();
        return response()->json([
            'id' => $articleImage->id,
            'deleted' => true,
            'default_id' => $article->imageDefault->id ?? null
        ]);
    }

    public function sortImages(Request $request, Article $article)
    {
        $this->authorize('update', $article);
        if($request->has('ids')){
            $ids = $request->get('ids');
            foreach ($ids as $key => $id) {
                $image = $article->images()->find($id);
                $image->update([
                    'position' => $key
                ]);
            }
        }
    }

    public function defaultImage(Request $request, Article $article, ArticleImage $articleImage)
    {
        $this->authorize('update', $article);
        $articleImage->is_default = true;
        $articleImage->save();
    }

    public function disableImage(Request $request, Article $article, ArticleImage $articleImage)
    {
        $this->authorize('update', $article);
        $articleImage->is_disabled = !$articleImage->is_disabled;
        $articleImage->save();

        return $articleImage;
    }

    protected function synRelations(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        // Salvo le immagini
        if($request->has('images')){
            foreach ($request->file('images') as $image) {
                $article->images()->create([
                    'article_id' => $article->id,
                    'image' => $image
                ]);
            }
        }
    }
}
