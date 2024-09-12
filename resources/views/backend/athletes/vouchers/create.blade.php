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
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo voucher') }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>

@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("athletes.vouchers.store", $athlete))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Athlete::class)
                                <x-backend.buttons.return route='{{ route("athletes.vouchers.index", $athlete) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Athlete::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include ("backend.athletes.vouchers.partials.form", ['disabled' => false])
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
