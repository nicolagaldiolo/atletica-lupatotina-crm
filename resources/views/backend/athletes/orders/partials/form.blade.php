@foreach ($articles as $article)
    @php
        $selected = old(
            'articles.' . $article->id . '.selected',
            in_array($article->id, $order->rows->pluck('article_id')->unique()->toArray()),
        );
    @endphp

    <div class="card @if (!$loop->first)mt-4 @endif">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if ($article->imageDefault)
                        <img style="width: 250px; max-width: 100%" src="{{ $article->imageDefault->public_url }}">
                    @endif

                </div>
                <div class="col-md-8">
                    <h4 class="mt-4">{{ $article->name }}</h4>
                    @money($article->price)

                    <div class="order_article_item">
                        <div class="mt-4">
                            <input type="hidden" value="0" name="articles[{{ $article->id }}][selected]" checked>
                            <input type="checkbox" class="btn-check order_article_switch" id="btn-check-outlined-{{ $article->id }}" autocomplete="off" value="1" name="articles[{{ $article->id }}][selected]" {{ $selected ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="btn-check-outlined-{{ $article->id }}">
                                <i class="fas fa-shopping-cart"></i> Aggiungi al carrello
                            </label>
                        </div>

                        <div class="variants_container mt-4 @if (!$selected) d-none @endif">
                            @if ($article->type == \App\Enums\ArticleType::Simple)
                                @php
                                    $quantity = $order->rows
                                        ->filter(function ($row) use ($article) {
                                            return $row->article_id == $article->id && $row->variant == null;
                                        })
                                        ->sum('quantity');

                                    $key = 0;
                                @endphp



                                @if ($errors->has('articles.' . $article->id . '.variants'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('articles.' . $article->id . '.variants') }}
                                    </div>
                                @endif
                                <div class="input-group has-validation mb-3">
                                    <span class="input-group-text" id="basic-addon1"><strong>Taglia
                                            Unica</strong></span>
                                    <input name="articles[{{ $article->id }}][variants][{{ $key }}]"
                                        class="form-control" type="number" step="1" min="0"
                                        value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                                </div>
                            @elseif($article->type == \App\Enums\ArticleType::Variants)
                                <div class="card">
                                    <div class="card-body">


                                        @if ($errors->has('articles.' . $article->id . '.variants'))
                                            <div class="alert alert-danger" role="alert">
                                                {{ $errors->first('articles.' . $article->id . '.variants') }}
                                            </div>
                                        @endif

                                        @foreach (App\Enums\Sizes::asSelectArray() as $key => $value)
                                            @php
                                                $quantity = $order->rows
                                                    ->filter(function ($row) use ($article, $key) {
                                                        return $row->article_id == $article->id &&
                                                            $row->variant == $key;
                                                    })
                                                    ->sum('quantity');
                                            @endphp

                                            <div class="input-group has-validation mb-3">
                                                <span style="width: 50px;" class="input-group-text text-uppercase"
                                                    id="basic-addon1"><strong>{{ $value }}</strong></span>
                                                <input
                                                    name="articles[{{ $article->id }}][variants][{{ $key }}]"
                                                    class="form-control" type="number" step="1" min="0"
                                                    value="{{ old('articles.' . $article->id . '.variants.' . $key, $quantity) }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('after-scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change', '.order_article_switch', function() {

                var variants = $(this).closest('.order_article_item').find('.variants_container');

                if ($(this).is(':checked')) {
                    variants.removeClass('d-none');
                } else {
                    variants.addClass('d-none');
                }
                variants.find('*').prop('disabled', !($(this).is(':checked')));

            });

        });
    </script>
@endpush
