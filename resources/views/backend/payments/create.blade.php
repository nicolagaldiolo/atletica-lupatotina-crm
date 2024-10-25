@extends('backend.layouts.app')

@php
    $entity = __('Aggiungi pagamenti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $entity }}

            <x-slot name="toolbar">
            </x-slot>
        </x-backend.section-header>
    </div>
    {{ html()->form('POST', route("payments.store"))->class('form')->open() }}
        <div class="card-body">
            @foreach ($athletes as $athlete)
                <div class="card card-accent-primary mb-3">
                    <div class="card-header">
                        {{ $athlete->fullname }}
                    </div>
                    <div class="card-body">
                        @foreach ($athlete->fees as $fee)
                            <div class="form-group mb-3">
                                <div class="form-check">

                                    <input class="form-check-input" type="checkbox" value="1" id="payments_{{ $athlete->id }}_{{ $fee->id }}" name="payments[{{ $athlete->id }}][{{ $fee->id }}]">
                                    <label class="form-check-label" for="payments_{{ $athlete->id }}_{{ $fee->id }}">
                                        <strong>{{ $fee->race->name }}</strong> ({{ $fee->name }} | @date($fee->expired_at))
                                        <br>
                                        <span>
                                            <strong>
                                                @money($fee->athletefee->custom_amount)
                                            </strong>
                                            @if($fee->athletefee->voucher)
                                                @php 
                                                    $amount_calculated = $fee->athletefee->voucher->amount_calculated
                                                @endphp
                                                
                                                @if($fee->athletefee->voucher->type == App\Enums\VoucherType::Credit)
                                                    <span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (@money($amount_calculated))</span>
                                                @else
                                                    <span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (@money($amount_calculated))</span>
                                                @endif
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('registerPayment', App\Models\Race::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>

@endsection

@push ('after-styles')


@endpush

@push ('after-scripts')

@endpush
