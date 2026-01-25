@extends('backend.layouts.app')

@php
    $entity = __('Gare');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $season->name }}</x-backend-breadcrumb-item>
@endsection

@section('content')
{{ html()->modelForm($season, 'PATCH', route("seasons.update", $season))->class('form')->open() }}
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col">
                <x-backend.buttons.delete route='{{ route("seasons.destroy", $season) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                <div class="float-end">
                    {{--@can('viewAnySeason', App\Models\Season::class)--}}
                        <x-backend.buttons.return route='{{ route("seasons.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    {{--@endcan--}}
                    {{--@can('updateSeason', App\Models\Season::class)--}}
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    {{--@endcan--}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.seasons.partials.form", ['disabled' => false])
            </div>
        </div>
    </div>
</div>

{{ html()->form()->close() }}

@endsection
