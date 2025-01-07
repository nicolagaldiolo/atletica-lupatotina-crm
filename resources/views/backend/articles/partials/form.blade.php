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
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Taglie disponibili') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @foreach(App\Enums\Sizes::asSelectArray() as $key => $value)
                                <div class="form-group mb-3">
                                    <label for="name">{{ $value }}</label>
                                    <span class="text-danger">*</span>
                                    <input name="variants[{{ $key }}]" class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" step="1" min="0" value="{{ old('quantity', $article->quantity) }}">
                                    @if ($errors->has('quantity'))
                                        <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            
        </div>
    
        

    </div>
    <div class="col-12 col-sm-6">

    </div>
</div>

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
