@extends('backend.layouts.app')

@php
    $entity = __('Gare');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.races.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $race->name }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    @include ("backend.races.partials.secondary_nav")
@endsection

@section('content')
{{ html()->modelForm($race, 'PATCH', route("backend.races.update", $race))->class('form')->open() }}
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $race)
                    <x-backend.buttons.delete route='{{ route("backend.races.destroy", $race) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
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

<div class="mt-3">
    <small class="float-end text-muted">
        Updated: {{$race->updated_at->diffForHumans()}},
        Created at: {{$race->created_at->isoFormat('LLLL')}}
    </small>
</div>

@endsection
