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
                    <div class="card card-accent-primary @if(!$loop->last) mb-3 @endif">
                        <div class="card-header">
                            {{ $athlete->fullname }}
                        </div>
                        <div class="card-body">
                            <table class="table table-stripped">
                                <tr>
                                    <th>{{ __('Gara') }}</th>
                                    <th>{{ __('Importo') }}</th>
                                    <th>{{ __('Bonifico') }}</th>
                                    <th>{{ __('Esattore') }}</th>
                                </tr>
                                @foreach ($athlete->fees as $fee)
                                    <tr>
                                        <td>
                                            <div class="form-group mb-3">
                                                <div class="form-check">
            
                                                    <input type="hidden" value="0" name="payments[{{ $athlete->id }}][{{ $fee->id }}][payed]">
                                                    <input class="form-check-input" type="checkbox" value="1" name="payments[{{ $athlete->id }}][{{ $fee->id }}][payed]" id="payments_{{ $athlete->id }}_{{ $fee->id }}">
                                                    <label class="form-check-label" for="payments_{{ $athlete->id }}_{{ $fee->id }}">
                                                        <strong>{{ $fee->race->name }}</strong> ({{ $fee->name }} | @date($fee->expired_at) | @money($fee->amount))
                                                        @if($fee->athletefee->voucher)
                                                            <br>
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
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>
                                                @money($fee->athletefee->custom_amount)
                                            </strong>
                                        </td>
                                        <td>
                                            <input type="hidden" value="0" name="payments[{{ $athlete->id }}][{{ $fee->id }}][bank_transfer]">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="payments[{{ $athlete->id }}][{{ $fee->id }}][bank_transfer]">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control" name="payments[{{ $athlete->id }}][{{ $fee->id }}][cashed_by]">
                                                @foreach ($accountants as $accountant)
                                                    <option @if(Auth::id() == $accountant->id) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
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
