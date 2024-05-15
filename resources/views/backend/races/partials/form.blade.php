<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $race->name) }}" @if($disabled) disabled @endif>
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="distance">{{ __('Distanza') }}</label>
            <input name="distance" class="form-control {{ $errors->has('distance') ? 'is-invalid' : '' }}" type="text" value="{{ old('distance', $race->distance) }}" @if($disabled) disabled @endif>
            @if ($errors->has('distance'))
                <div class="invalid-feedback">{{ $errors->first('distance') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="date">{{ __('Data') }}</label>
            <span class="text-danger">*</span>
            <input name="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" type="date" value="{{ old('date', App\Classes\Utility::dateFormatted($race->date)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('date'))
                <div class="invalid-feedback">{{ $errors->first('date') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="is_subscrible">{{ __('Iscrizioni aperte') }}</label>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_subscrible" type="hidden" checked value="0" @if($disabled) disabled @endif>
                <input class="form-check-input {{ $errors->has('is_subscrible') ? 'is-invalid' : '' }}" type="checkbox" name="is_subscrible" {{ old('is_subscrible', $race->is_subscrible) ? 'checked' : "" }} value="1" @if($disabled) disabled @endif>
                @if ($errors->has('is_subscrible'))
                <div class="invalid-feedback">{{ $errors->first('is_subscrible') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="subscrible_expiration">{{ __('Iscrizioni aperte fino al') }}</label>
            <input name="subscrible_expiration" class="form-control {{ $errors->has('subscrible_expiration') ? 'is-invalid' : '' }}" type="date" value="{{ old('subscrible_expiration', App\Classes\Utility::dateFormatted($race->subscrible_expiration)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('subscrible_expiration'))
                <div class="invalid-feedback">{{ $errors->first('subscrible_expiration') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="is_visible_on_site">{{ __('Visualizza sul sito') }}</label>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_visible_on_site" type="hidden" checked value="0" @if($disabled) disabled @endif>
                <input class="form-check-input {{ $errors->has('is_visible_on_site') ? 'is-invalid' : '' }}" type="checkbox" name="is_visible_on_site" {{ old('is_visible_on_site', $race->is_visible_on_site) ? 'checked' : "" }} value="1" @if($disabled) disabled @endif>
                @if ($errors->has('is_visible_on_site'))
                <div class="invalid-feedback">{{ $errors->first('is_visible_on_site') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="amount">{{ __('Importo') }}</label>
            <span class="text-danger">*</span>
            <input name="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" step=".01" value="{{ old('amount', $race->amount) }}" @if($disabled) disabled @endif>
            @if ($errors->has('amount'))
                <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
            @endif
        </div>
    </div>
</div>

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
