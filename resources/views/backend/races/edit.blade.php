@extends('backend.layouts.app')

@php
    $entity = __('Gare');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $race->name }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.races.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
{{ html()->modelForm($race, 'PATCH', route("races.update", $race))->class('form')->open() }}
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $race)
                    <x-backend.buttons.delete route='{{ route("races.destroy", $race) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('viewAny', App\Models\Race::class)
                        <x-backend.buttons.return route='{{ route("races.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $race)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.races.partials.form", ['disabled' => false])
            </div>
        </div>
    </div>
</div>

{{ html()->form()->close() }}

@endsection
