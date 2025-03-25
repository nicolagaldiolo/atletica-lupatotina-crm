@extends('backend.layouts.app')

@php
    $entity = __('Quote iscrizione')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', [$race->type, $race]) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $fee->name }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.races.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($fee, 'PATCH', route("races.fees.update", [$race->type, $race, $fee]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                @canany(['deleteRace','deleteTrack'], $fee)
                    <x-backend.buttons.delete route='{{ route("races.fees.destroy", [$race->type, $race, $fee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcanany
                <div class="float-end">
                    @canany(['viewAnyRace', 'viewAnyTrack'], App\Models\Fee::class)
                        <x-backend.buttons.return route='{{ route("races.fees.index", [$race->type, $race]) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    @endcanany
                    @canany(['updateRace', 'updateTrack'], $fee)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcanany
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
