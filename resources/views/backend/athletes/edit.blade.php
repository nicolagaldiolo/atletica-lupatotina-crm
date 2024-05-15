@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.athletes.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.secondary_nav")
@endsection

@section('content')

{{ html()->modelForm($athlete, 'PATCH', route("backend.athletes.update", $athlete))->class('form')->open() }}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $athlete)
                    <x-backend.buttons.delete route='{{ route("backend.athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('update', $athlete)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
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
</div>

{{ html()->form()->close() }}

<div class="mt-3">
    <small class="float-end text-muted">
        Updated: {{$athlete->updated_at->diffForHumans()}},
        Created at: {{$athlete->created_at->isoFormat('LLLL')}}
    </small>
</div>

@endsection
