<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $season->name) }}" @if($disabled) disabled @endif>
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="date">{{ __('Data inizio') }}</label>
            <span class="text-danger">*</span>
            <input name="start_at" class="form-control {{ $errors->has('start_at') ? 'is-invalid' : '' }}" type="date" value="{{ old('start_at', App\Classes\Utility::dateFormatted($season->start_at)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('start_at'))
                <div class="invalid-feedback">{{ $errors->first('start_at') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="date">{{ __('Data fine') }}</label>
            <span class="text-danger">*</span>
            <input name="end_at" class="form-control {{ $errors->has('end_at') ? 'is-invalid' : '' }}" type="date" value="{{ old('end_at', App\Classes\Utility::dateFormatted($season->end_at)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('end_at'))
                <div class="invalid-feedback">{{ $errors->first('end_at') }}</div>
            @endif
        </div>
    </div>
</div>

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
