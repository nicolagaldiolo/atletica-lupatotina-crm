<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $voucher->name) }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="is_current">{{ __('Tipo') }}</label>
            <span class="text-danger">*</span>
            
            <select name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                @foreach (App\Enums\VoucherType::asSelectArray() as $key => $value)
                    <option value="{{ $key }}" @if ($key == old('type', $voucher->type)) selected @endif>{{ $value }}</option>
                @endforeach
            </select>

            @if ($errors->has('type'))
                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Importo') }}</label>
            <span class="text-danger">*</span>
            <input name="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" step="0.1" value="{{ old('amount', $voucher->amount) }}">
            @if ($errors->has('amount'))
                <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
            @endif
        </div>
    </div>
</div>
