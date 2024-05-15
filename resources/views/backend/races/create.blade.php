@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.races.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Nuova gara') }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ __('Aggiungi gara') }}
            <x-slot name="toolbar">
                @can('viewAny', App\Models\Race::class)
                    <x-backend.buttons.return route='{{ route("backend.races.index") }}' icon="fas fa-reply" small="true" />
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
    {{ html()->form('POST', route("backend.races.store"))->class('form')->open() }}
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include ("backend.races.partials.form", ['disabled' => false])
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Race::class)
                                <x-backend.buttons.return route='{{ route("backend.races.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
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
