@extends('backend.layouts.app')

@php
    $entity = __('Quote iscrizione')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route="{{ route('races.index') }}">{{ __('Gare') }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('races.edit', $race) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('races.fees.index', $race) }}">{{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $fee->name }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($fee, 'PATCH', route("races.fees.update", [$race, $fee]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $fee)
                    <x-backend.buttons.delete route='{{ route("races.fees.destroy", [$race, $fee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('update', $fee)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.races.fees.partials.form", ['disabled' => false])
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
