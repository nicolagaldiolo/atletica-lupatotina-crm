<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="is_active">{{ __('Attivo') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_active" type="hidden" checked value="0">
                <input class="form-check-input {{ $errors->has('is_active') ? 'is-invalid' : '' }}"
                    type="checkbox" name="is_active"
                    {{ old('is_active', $size->is_active) ? 'checked' : '' }} value="1">
                @if ($errors->has('is_active'))
                    <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                type="text" value="{{ old('name', $size->name) }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
        
    </div>
</div>

@push('after-styles')
@endpush

@push('after-scripts')

@endpush
