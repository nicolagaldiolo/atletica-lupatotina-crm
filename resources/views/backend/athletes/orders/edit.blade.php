@extends('backend.layouts.app')

@php
    $entity = __('Ordini')
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="true" route="#">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $order->created_at }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($order, 'PATCH', route("athletes.orders.update", [$athlete, $order]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                {{--@can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $fee)--}}
                    <x-backend.buttons.delete route='{{ route("athletes.orders.destroy", [$athlete, $order]) }}'data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}" />
                {{--@endcan--}}
                <div class="float-end">
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)--}}
                        <x-backend.buttons.return route='{{ route("athletes.orders.index", [$athlete]) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    {{--@endcan--}}
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
                        <x-backend.buttons.save small="true">{{__('Salva')}}</x-backend.buttons.save>
                    {{--@endcan--}}
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.orders.partials.form", ['disabled' => false])
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                

                {{--@can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $fee)--}}
                    <x-backend.buttons.delete route='{{ route("athletes.orders.destroy", [$athlete, $order]) }}'data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}" />
                {{--@endcan--}}
                <div class="float-end">
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)--}}
                        <x-backend.buttons.return route='{{ route("athletes.orders.index", [$athlete]) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    {{--@endcan--}}
                    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
                        <x-backend.buttons.save small="true">{{__('Salva')}}</x-backend.buttons.save>
                    {{--@endcan--}}
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
