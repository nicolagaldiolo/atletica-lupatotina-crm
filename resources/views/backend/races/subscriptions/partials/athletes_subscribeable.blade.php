<div class="row">
    <div class="col-12">
        <h6 class="card-title">{{ __('Atleti') }}</h6>

        @foreach ($athletes as $athlete)
                <ul class="athlete_subscription_list list-group mb-2">
                    <li class="list-group-item">
                        <div class="form-check form-switch">
                            <input class="athlete_subscription_switch form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault_{{ $athlete->id}}">
                            <label class="form-check-label" for="flexSwitchCheckDefault_{{ $athlete->id}}">{{ $athlete->fullname }}</label>
                        </div>
                    </li>
                    <li class="list-group-item athlete_subscription_form d-none">
                        <div class="row">
                            <div class="col">

                                <div class="input-group">
                                    <input disabled name="athletes[{{$athlete->id}}][custom_amount]" value="{{ $fee->amount }}" type="number" step="0.1" class="form-control">
                                    @if($athlete->validVouchers->count())
                                        <span class="input-group-text">
                                            <label>
                                                <input disabled name="athletes[{{$athlete->id}}][voucher_id]" checked class="form-check-input mt-0" type="radio" value="">
                                                <span class="badge text-bg-secondary">Nessun Voucher</span>
                                            </label>
                                        </span>
                                        @foreach ($athlete->validVouchers as $voucher)
                                            <span class="input-group-text">
                                                <label>
                                                    <input disabled name="athletes[{{$athlete->id}}][voucher_id]" class="form-check-input mt-0" type="radio" value="{{ $voucher->id}}">
                                                    <span class="badge @if($voucher->type == App\Enums\VoucherType::Credit) text-bg-success @else text-bg-danger @endif ">({{ App\Enums\VoucherType::getDescription($voucher->type) }}) | {{ $voucher->name }} | @money($voucher->amount_calculated)</span>
                                                </label>
                                            </span>
                                        @endforeach
                                    @endif
                                    
                                </div>

                            </div>
                        </div>
                        
                    </li>
                </ul>
            @endforeach
    </div>
</div>