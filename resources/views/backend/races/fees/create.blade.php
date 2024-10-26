@extends('backend.layouts.app')

@php
    $entity = __('Quote iscrizione')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', $race) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Nuova quota') }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.races.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("races.fees.store", $race))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Fee::class)
                                <x-backend.buttons.return route='{{ route("races.fees.index", $race) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Fee::class)
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
                    @include ("backend.races.fees.partials.form", ['disabled' => false])
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
