@extends('backend.layouts.app')

@php
    $entity = __('Certiticati')
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canrul="{{ Auth::user()->can('edit', $athlete) }}" route='{{route("athletes.edit", $athlete)}}'>{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $fee->race->name }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($athleteFee, 'PATCH', route("athletes.fees.athletefee.update", [$athlete, $raceType, $fee, $athleteFee]))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    @can((($raceType == App\Enums\RaceType::Race) ? 'subscribeRace' : (($raceType == App\Enums\RaceType::Track) ? 'subscribeTrack' : false)), $athleteFee)
                        <x-backend.buttons.delete route='{{ route("athletes.fees.athletefee.destroySubscription", [$athlete, $raceType, $fee, $athleteFee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                    @endcan

                    <div class="float-end">
                        <div class="form-group">
                            @if(
                                ($raceType == App\Enums\RaceType::Race ? Gate::any(['subscribeRace', 'registerPaymentRace'], $athleteFee) : ($raceType == App\Enums\RaceType::Track ? Gate::any(['subscribeTrack', 'registerPaymentTrack'], $athleteFee) : false)) || 
                                Gate::check('viewAny', [AthleteFee::class, $athlete])
                            )
                                <x-backend.buttons.return route='{{ route("athletes.fees.index", [$athlete, $fee->race->type]) }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                            @endif

                            @can((($raceType == App\Enums\RaceType::Race) ? 'registerPaymentRace' : (($raceType == App\Enums\RaceType::Track) ? 'registerPaymentTrack' : false)), $athleteFee)
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
                    @include ("backend.athletes.fees.partials.form", ['disabled' => false])
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
