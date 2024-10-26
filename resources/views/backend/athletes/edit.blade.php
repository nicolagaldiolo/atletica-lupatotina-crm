@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
{{ html()->modelForm($athlete, 'PATCH', route("athletes.update", $athlete))->class('form')->open() }}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $athlete)
                    <x-backend.buttons.delete route='{{ route("athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('viewAny', App\Models\Athlete::class)
                        <x-backend.buttons.return route='{{ route("athletes.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
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

@endsection
