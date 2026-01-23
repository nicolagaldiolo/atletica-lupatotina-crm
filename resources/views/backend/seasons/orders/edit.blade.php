@extends('backend.layouts.app')

@php
    $entity = __('Quote iscrizione')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    {{-- 
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', [$race->type, $race]) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $fee->name }}</x-backend-breadcrumb-item>
    --}}
@endsection

@section('secondary-nav')
    {{--@include ("backend.races.partials.action_column", ['layout' => 'nav'])--}}
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($order, 'PATCH', route("seasons.orders.update", [$season, $order]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                

                {{--@can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $fee)--}}
                    <x-backend.buttons.delete route='{{ route("seasons.orders.destroy", [$season, $order]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                {{--@endcan--}}
                <div class="float-end">
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)--}}
                        <x-backend.buttons.return route='{{ route("seasons.orders.index", [$season]) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    {{--@endcan--}}
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    {{--@endcan--}}
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.seasons.orders.partials.form", ['disabled' => false])
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
