<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>{{ __('Articolo') }}</th>
            <th>{{ __('Quantità') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($articles as $article )
            
            <tr>
                <td>
                    <div class="form-check form-switch">
                        <input type="hidden" value="0" name="articles[{{ $article->id }}][selected]" checked>
                        <input class="form-check-input" type="checkbox" value="1" name="articles[{{ $article->id }}][selected]" @if(in_array($article->id, $order->rows->pluck('article_id')->unique()->toArray())) checked @endif>
                    </div>
                    <img src="https://picsum.photos/100" class="" alt="..." style="float:left">
                    <h3>{{ $article->name }}</h3>
                    <strong>{{ $article->price }} €</strong>
                </td>
                <td>
                    <div class="form-group mb-3">

                        <label for="name">{{ __('Quantità') }}</label>
                        <span class="text-danger">*</span>

                        @if($article->type == \App\Enums\ArticleType::Simple)

                            @php
                                $quantity = ($order->rows->first(function($row) use($article){
                                    return $row->article_id == $article->id && $row->variant == null;
                                })->quantity ?? 0);

                                $key = 0;
                            @endphp

                            <input name="articles[{{ $article->id }}][variants][{{ $key }}]" class="form-control {{ $errors->has('articles.' . $article->id . '.variants.' . $key) ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                            @if ($errors->has('variants.' . $key))
                                <div class="invalid-feedback">{{ $errors->first('variants.' . $key) }}</div>
                            @endif

                        @elseif($article->type == \App\Enums\ArticleType::Variants)

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            @foreach(App\Enums\Sizes::asSelectArray() as $key => $value)

                                                @php
                                                    $quantity = ($order->rows->first(function($row) use($article, $key){
                                                        return $row->article_id == $article->id && $row->variant == $key;
                                                    })->quantity ?? 0);
                                                @endphp

                                                <div class="form-group mb-3">
                                                    <label for="name">{{ $value }}</label>
                                                    <span class="text-danger">*</span>
                                                    <input name="articles[{{ $article->id }}][variants][{{ $key }}]" class="form-control {{ $errors->has('articles.' . $article->id . '.variants.' . $key) ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                                                    @if ($errors->has('variants.' . $key))
                                                        <div class="invalid-feedback">{{ $errors->first('variants.' . $key) }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                        
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>