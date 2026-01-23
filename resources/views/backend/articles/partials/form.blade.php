<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="is_active">{{ __('Attivo') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_active" type="hidden" checked value="0">
                <input class="form-check-input {{ $errors->has('is_active') ? 'is-invalid' : '' }}" type="checkbox" name="is_active" {{ old('is_active', $article->is_active) ? 'checked' : "" }} value="1">
                @if ($errors->has('is_active'))
                <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>

        <input name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="hidden" value="{{ $article->type }}">

        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $article->name) }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
        <div class="form-group mb-3">
            <label for="name">{{ __('Prezzo') }}</label>
            <span class="text-danger">*</span>
            <input name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" step=".01" value="{{ old('price', $article->price) }}">
            @if ($errors->has('price'))
                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="is_unlimited">{{ __('Magazzino infinito') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_unlimited" type="hidden" checked value="0">
                <input class="form-check-input {{ $errors->has('is_unlimited') ? 'is-invalid' : '' }}" type="checkbox" name="is_unlimited" {{ old('is_unlimited', $article->is_unlimited) ? 'checked' : "" }} value="1">
                @if ($errors->has('is_unlimited'))
                <div class="invalid-feedback">{{ $errors->first('is_unlimited') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="name">{{ __('Quantità') }}</label>
            <span class="text-danger">*</span>
            
            @if($article->type == \App\Enums\ArticleType::Simple)
                <input name="quantity" class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('quantity', ($article->quantity ? $article->quantity : 0)) }}">
                @if ($errors->has('quantity'))
                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                @endif
            @elseif($article->type == \App\Enums\ArticleType::Variants)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @foreach(App\Enums\Sizes::asSelectArray() as $key => $value)
                                    <div class="form-group mb-3">
                                        <label for="name">{{ $value }}</label>
                                        <span class="text-danger">*</span>
                                        <input name="variants[{{ $key }}]" class="form-control {{ $errors->has('variants.' . $key) ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('variants.' . $key, $article->variants[$key] ?? 0) }}">
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
    </div>
    <div class="col-12 col-sm-6">

    </div>
</div>

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
