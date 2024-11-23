<h5>
    {{ $fee->race->name }}
    <small>({{ $fee->name }} - @money($fee->amount)) </small>
    @if($athleteFee->voucher)
        @if($athleteFee->voucher->type == App\Enums\VoucherType::Credit)
            <span class="badge text-bg-success">
                {{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} @money($athleteFee->voucher->amount)
            </span>
        @elseif($athleteFee->voucher->type == App\Enums\VoucherType::Penalty)
            <span class="badge text-bg-danger">
                {{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} @money($athleteFee->voucher->amount)
            </span>
        @endif
    @endif
</h5>

<div class="row mt-4">
    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="payed">{{ __('Pagato') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="payed" class="form-item" type="hidden" checked value="0">
                <input class="form-check-input form-item {{ $errors->has('payed') ? 'is-invalid' : '' }}" type="checkbox" name="payed" {{ old('payed', $athleteFee->payed_at) ? 'checked' : "" }} value="1">
                @if ($errors->has('payed'))
                    <div class="invalid-feedback">{{ $errors->first('payed') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Importo') }}</label>
            <input disabled class="form-control" type="text" value="@money($athleteFee->custom_amount)">
        </div>
    </div>

    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="bank_transfer">{{ __('Pagato con bonifico') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="bank_transfer" class="form-item" type="hidden" checked value="0">
                <input class="form-check-input form-item {{ $errors->has('bank_transfer') ? 'is-invalid' : '' }}" type="checkbox" name="bank_transfer" {{ old('bank_transfer', $athleteFee->bank_transfer) ? 'checked' : "" }} value="1">
                @if ($errors->has('bank_transfer'))
                    <div class="invalid-feedback">{{ $errors->first('bank_transfer') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="bank_transfer">{{ __('Esattore') }}</label>
            <span class="text-danger">*</span>
            <select class="form-select {{ $errors->has('cashed_by') ? 'is-invalid' : '' }}" name="cashed_by" id="inputGroupSelect01">
                @foreach ($accountants as $accountant)
                    <option @if(old('cashed_by', $athleteFee->cashed_by ? $athleteFee->cashed_by : Auth::id()) == $accountant->id) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('cashed_by'))
                <div class="invalid-feedback">{{ $errors->first('cashed_by') }}</div>
            @endif
        </div>
    </div>
</div>
