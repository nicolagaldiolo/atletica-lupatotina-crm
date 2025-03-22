<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="type">{{ __('Tipologia') }}</label>
            <span class="text-danger">*</span>
            <input name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" value="{{ old('type', $race->type) }}" readonly @if($disabled) disabled @endif>
            @if ($errors->has('type'))
                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            @endif
        </div>
    </div>
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

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
