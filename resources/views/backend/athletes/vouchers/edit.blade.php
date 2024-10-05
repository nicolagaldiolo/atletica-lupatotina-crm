@extends('backend.layouts.app')

@php
    $entity = __('Voucher')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route="{{ route('athletes.index') }}">{{ __('Atleti') }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('athletes.edit', $athlete) }}">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('athletes.vouchers.index', $athlete) }}">{{ $entity }}</x-backend-breadcrumb-item>
    @if($voucher)
        <x-backend-breadcrumb-item type="active">{{ $voucher->name }}</x-backend-breadcrumb-item>
    @endif
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($voucher, 'PATCH', route("athletes.vouchers.update", [$athlete, $voucher]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $voucher)
                    <x-backend.buttons.delete route='{{ route("athletes.vouchers.destroy", [$athlete, $voucher]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('viewAny', App\Models\Voucher::class)
                        <x-backend.buttons.return route='{{ route("athletes.vouchers.index", $athlete) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $voucher)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.vouchers.partials.form")
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
