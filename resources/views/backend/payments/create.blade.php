@extends('backend.layouts.app')

@php
    $entity = __('Pagamenti atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('content')

@if(!$athletes->count())
    <div class="card text-center">
        <div class="card-body p-5">
            <i class="fa-solid fa-coins fa-5x opacity-50"></i>
            <h5 class="mt-4">{{ __('Nessun pagamento da registrare') }}</h5>
        </div>
    </div>
@else
    <div class="card">
        {{ html()->form('POST', route("payments.store"))->class('form')->open() }}
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <div class="float-end">
                            @can('registerPayment', App\Models\AthleteFee::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @foreach ($athletes as $athlete)
                    <table class="table table-bordered table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th colspan="4">{{ $athlete->fullname }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{ __('Gara') }}</th>
                                <th>{{ __('Pagamento') }}</th>
                            </tr>
                            @foreach ($athlete->fees as $fee)
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-check">
                                                @php 
                                                    $id_checkbox = 'payments_' . $athlete->id . '_' . $fee->id; 
                                                @endphp
                                                <input type="hidden" value="0" name="payments[{{ $athlete->id }}][{{ $fee->id }}][payed]">
                                                <input class="form-check-input" type="checkbox" value="1" name="payments[{{ $athlete->id }}][{{ $fee->id }}][payed]" id="{{ $id_checkbox }}">
                                                <label class="form-check-label" for="{{ $id_checkbox }}">
                                                    <strong>{{ $fee->race->name }}</strong> ({{ $fee->name }} | @date($fee->expired_at) | @money($fee->amount))
                                                    <div>
                                                        <span class="badge text-bg-primary">
                                                            <i class="nav-icon fas fa-coins"></i> @money($fee->athletefee->custom_amount)
                                                        </span>
                                                        @if($fee->athletefee->voucher)
                                                            <span>
                                                                @php 
                                                                    $amount_calculated = $fee->athletefee->voucher->amount_calculated
                                                                @endphp
                                                                
                                                                @if($fee->athletefee->voucher->type == App\Enums\VoucherType::Credit)
                                                                    <span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (@money($amount_calculated))</span>
                                                                @else
                                                                    <span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (@money($amount_calculated))</span>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <div class="form-check form-switch">
                                                    @php 
                                                        $id_switch = 'flexSwitchCheckDefault_' . $athlete->id . '_' . $fee->id; 
                                                    @endphp
                                                    <input type="hidden" value="0" name="payments[{{ $athlete->id }}][{{ $fee->id }}][bank_transfer]">
                                                    <input class="form-check-input" type="checkbox" value="1" name="payments[{{ $athlete->id }}][{{ $fee->id }}][bank_transfer]" role="switch" id="{{ $id_switch }}">
                                                    <label class="form-check-label" for="{{ $id_switch }}"><i class="fa-solid fa-landmark"></i> {{ __('Bonifico') }}</label>
                                                </div>
                                            </div>
                                            <select class="form-select" name="payments[{{ $athlete->id }}][{{ $fee->id }}][cashed_by]" id="inputGroupSelect01">
                                                @foreach ($accountants as $accountant)
                                                    <option @if(Auth::id() == $accountant->id) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <div class="float-end">
                            <div class="form-group">
                                @can('registerPayment', App\Models\AthleteFee::class)
                                    <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
@endif

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
@endpush
