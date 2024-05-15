<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $fee->name) }}" @if($disabled) disabled @endif>
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="expired_at">{{ __('Valida fino al') }}</label>
            <input name="expired_at" class="form-control {{ $errors->has('expired_at') ? 'is-invalid' : '' }}" type="date" value="{{ old('expired_at', App\Classes\Utility::dateFormatted($fee->expired_at)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('expired_at'))
                <div class="invalid-feedback">{{ $errors->first('expired_at') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="amount">{{ __('Importo') }}</label>
            <span class="text-danger">*</span>
            <input name="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" step=".01" value="{{ old('amount', $fee->amount) }}" @if($disabled) disabled @endif>
            @if ($errors->has('amount'))
                <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
            @endif
        </div>
    </div>
</div>
