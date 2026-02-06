<ul class="list-group mb-2">
@foreach ($articles as $article )

    @php
        $selected = old('articles.' . $article->id . '.selected', in_array($article->id, $order->rows->pluck('article_id')->unique()->toArray()));
    @endphp      

    
        <li class="list-group-item order_article_item">
            <div class="row">
                <div class="col-md-3">
                    @if($article->imageDefault)
                        <img width="300" src="{{ $article->imageDefault->public_url }}">
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="">
                        <span>{{ $article->name }}</span>
                        <h4>@money($article->price)</h4>
                    </div>

                    <div class="form-check form-switch">
                        <input type="hidden" value="0" name="articles[{{ $article->id }}][selected]" checked>
                        <input class="form-check-input order_article_switch" type="checkbox" value="1" name="articles[{{ $article->id }}][selected]" {{ $selected ? 'checked' : "" }}>
                    </div>

                    <div class="variants_container @if(!$selected) d-none @endif">
                        @if($article->type == \App\Enums\ArticleType::Simple)

                            @php
                                $quantity = $order->rows->filter(function($row) use($article){
                                    return $row->article_id == $article->id && $row->variant == null;
                                })->sum('quantity');

                                $key = 0;
                            @endphp

                            

                            @if ($errors->has('articles.' . $article->id . '.variants'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('articles.' . $article->id . '.variants') }}
                                </div>
                            @endif
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text" id="basic-addon1"><strong>Taglia Unica</strong></span>
                                <input name="articles[{{ $article->id }}][variants][{{ $key }}]" class="form-control" type="number" step="1" min="0" value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                            </div>
                            
                            
                        @elseif($article->type == \App\Enums\ArticleType::Variants)

                            <div class="card">
                                <div class="card-body">
                                    
                                            
                                            @if ($errors->has('articles.' . $article->id . '.variants'))
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $errors->first('articles.' . $article->id . '.variants') }}
                                                </div>
                                            @endif

                                            @foreach(App\Enums\Sizes::asSelectArray() as $key => $value)
                                                @php
                                                    $quantity = $order->rows->filter(function($row) use($article, $key){
                                                        return $row->article_id == $article->id && $row->variant == $key;
                                                    })->sum('quantity');
                                                @endphp

                                                <div class="input-group has-validation mb-3">
                                                    <span style="width: 50px;" class="input-group-text text-uppercase" id="basic-addon1"><strong>{{ $value }}</strong></span>
                                                    <input name="articles[{{ $article->id }}][variants][{{ $key }}]" class="form-control" type="number" step="1" min="0" value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                                                </div>                                                
                                            @endforeach
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </li>
@endforeach
</ul>

@push ('after-scripts')

<script type="text/javascript">
    $(document).ready(function() {
        
        $(document).on('change', '.order_article_switch', function(){

            var variants = $(this).closest('.order_article_item').find('.variants_container');

            if($(this).is(':checked')){
                variants.removeClass('d-none');
            }else{
                variants.addClass('d-none');
            }
            variants.find('*').prop('disabled', !($(this).is(':checked')));
            
        });

    });
</script>

@endpush
