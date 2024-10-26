@extends('backend.layouts.app')

@php
    $entity = __('Atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo atleta') }}</x-backend-breadcrumb-item>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("athletes.store"))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Athlete::class)
                                <x-backend.buttons.return route='{{ route("athletes.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Athlete::class)
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
                    @include ("backend.athletes.partials.form", ['disabled' => false])
                </div>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>

@endsection
