@extends('backend.layouts.app')

@php
    $entity = App\Enums\RaceType::getKey($race->type);
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Nuova gara') }}</x-backend-breadcrumb-item>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("races.store", $race->type))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">

                            @canAny(['viewAnyRace', 'viewAnyTrack'], App\Models\Race::class)
                                <x-backend.buttons.return route='{{ route("races.index", $race->type) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcanany
                            @canAny(['createRace', 'createTrack'], App\Models\Race::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcanany
                        </div>
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

    {{ html()->form()->close() }}
</div>

@endsection
