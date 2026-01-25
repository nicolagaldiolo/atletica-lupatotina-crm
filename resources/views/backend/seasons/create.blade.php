@extends('backend.layouts.app')

@php
    $entity = __('Stagioni')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Nuova stagione') }}</x-backend-breadcrumb-item>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("seasons.store"))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            {{--@can('viewAnySeason', App\Models\Season::class)--}}
                                <x-backend.buttons.return route='{{ route("seasons.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            {{--@endcan--}}
                            
                            {{--@can('createSeason', App\Models\Season::class)--}}
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            {{--@endcan--}}
                        </div>
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

    {{ html()->form()->close() }}
</div>

@endsection
