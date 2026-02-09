@extends('backend.layouts.app')

@php
    $entity = __('Taglie')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Nuova taglia') }}</x-backend-breadcrumb-item>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("sizes.store"))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Size::class)
                                <x-backend.buttons.return route='{{ route("sizes.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Size::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include ("backend.sizes.partials.form")
        </div>
    {{ html()->form()->close() }}
</div>

@endsection
