@php
    $bank_transfer = old('bank_transfer', ($orderRow->transactions->first()->bank_transfer ?? false));
    $invisible_class = 'invisible';
@endphp


<h5>
    {{ $order->created_at }}
</h5>

<div class="row mt-4">
    <div class="col-12 col-sm-3">
        <div class="form-group mb-3">
            <label for="name">{{ __('Importo') }}</label>
            <input disabled class="form-control" type="text" value="@money($orderRow->total_amount)">
        </div>
    </div>

    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="status">{{ __('Status') }}</label>
            <span class="text-danger">*</span>
            <select class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status">
                @foreach (App\Enums\OrderRowStatus::asSelectArray() as $key => $value)
                    <option value="{{ $key }}" @if($key == old('status', $orderRow->status)) selected @endif>{{ __($value) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="payed">{{ __('Pagato') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="payed" class="form-item" type="hidden" checked value="0">
                <input class="form-check-input form-item {{ $errors->has('payed') ? 'is-invalid' : '' }}" type="checkbox" name="payed" {{ old('payed', $orderRow->is_payed) ? 'checked' : "" }} value="1">
                @if ($errors->has('payed'))
                    <div class="invalid-feedback">{{ $errors->first('payed') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="bank_transfer">{{ __('Pagato con bonifico') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="bank_transfer" class="form-item" type="hidden" checked value="0">
                <input class="form-check-input form-item input-switch {{ $errors->has('bank_transfer') ? 'is-invalid' : '' }}" type="checkbox" name="bank_transfer" {{ $bank_transfer ? 'checked' : "" }} value="1">
                @if ($errors->has('bank_transfer'))
                    <div class="invalid-feedback">{{ $errors->first('bank_transfer') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-3">
        <div class="form-group mb-3 cashed_by_container @if($bank_transfer) {{ $invisible_class }} @endif">
            <label for="bank_transfer">{{ __('Esattore') }}</label>
            <span class="text-danger">*</span>
            <select class="form-select {{ $errors->has('cashed_by') ? 'is-invalid' : '' }}" name="cashed_by">
                @foreach ($accountants as $accountant)
                    <option @if(old('cashed_by', ($orderRow->transactions->first()->cashed_by ?? Auth::id()) == $accountant->id)) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('cashed_by'))
                <div class="invalid-feedback">{{ $errors->first('cashed_by') }}</div>
            @endif
        </div>
    </div>
</div>


@push ('after-scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.input-switch[name="bank_transfer"]').on('change', function(event) {
            let is_checked = $(this).is(':checked');
            let item = $('.cashed_by_container');
            if(is_checked){
                item.addClass('{{ $invisible_class }}');
            }else{
                item.removeClass('{{ $invisible_class }}');
            }
        });
    });
</script>
@endpush