@extends('backend.layouts.app')

@php
    $entity = $athlete->fullname;
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item>{{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $race->name }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $entity }}

            <x-slot name="toolbar">
                @can('viewAny', App\Models\Race::class)
                    <x-backend.buttons.return route='{{ route("backend.races.athletes.index", $race) }}' icon="fas fa-reply" small="true" />
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
    {{ html()->form('PATCH', route("backend.races.athletes.update", [$race, $athlete]))->class('form')->open() }}
        <div class="card-body">
            @include ("backend.races.athletes.partials.form")
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Race::class)
                                <x-backend.buttons.return route='{{ route("backend.races.athletes.index", [$race]) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Race::class)
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
