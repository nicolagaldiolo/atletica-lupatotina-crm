@extends('backend.layouts.app')

@php
    $entity = __('Gare')
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
            {{ __('Aggiungi quota') }}
            <x-slot name="toolbar">
                @can('viewAny', App\Models\Athlete::class)
                    <x-backend.buttons.return route='{{ route("backend.athletes.index") }}' icon="fas fa-reply" small="true" />
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
    {{ html()->form('POST', route("backend.races.fees.store", $race))->class('form')->open() }}
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include ("backend.races.fees.partials.form", ['disabled' => false])
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Athlete::class)
                                <x-backend.buttons.return route='{{ route("backend.athletes.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Athlete::class)
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
