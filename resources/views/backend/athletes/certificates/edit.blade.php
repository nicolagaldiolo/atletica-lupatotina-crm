@extends('backend.layouts.app')

@php
    $entity = __('Certiticati')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route="{{ route('backend.athletes.index') }}">{{ __('Atleti') }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('backend.athletes.edit', $athlete) }}">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('backend.athletes.certificates.index', $athlete) }}">{{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $certificate->status['date'] }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("backend.athletes.certificates.index", $certificate) }}">
                            <i class="far fa-times-circle"></i>
                            {{ __('Chiudi') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($certificate, 'PATCH', route("backend.athletes.certificates.update", [$athlete, $certificate]))->acceptsFiles()->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                {{--@can('delete', $race)
                    <x-backend.buttons.delete route='{{ route("backend.races.fees.destroy", [$race, $fee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan--}}
                <div class="float-end">
                    {{--@can('update', $race)--}}
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    {{--@endcan--}}
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.certificates.partials.form", ['disabled' => false])
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
