    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>{{ __('Articolo') }}</th>
                <th>{{ __('Varianti') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article )
                <tr>
                    <td>
                        <div class="form-check form-switch">
                            <input type="hidden" value="0" name="article[{{ $article->id }}][selected]" checked>
                            <input class="form-check-input" type="checkbox" value="1" name="article[{{ $article->id }}][selected]">
                        </div>
                        <img src="https://picsum.photos/100" class="" alt="...">
                        <strong>{{ $article->price }} €</strong>
                        <h3>{{ $article->name }}</h3>
                    </td>
                    <td>
                        @foreach(App\Enums\Sizes::asSelectArray() as $key => $value)
                            <div class="form-group mb-3">
                                <label for="name">{{ $value }}</label>
                                <span class="text-danger">*</span>
                                <input name="article[{{ $article->id }}][variants][{{ $key }}]" class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('quantity', $article->quantity) }}">
                                @if ($errors->has('quantity'))
                                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                @endif
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>