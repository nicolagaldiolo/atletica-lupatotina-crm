@extends('backend.layouts.app')

@php
    $entity = __('Certiticati')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route="{{ route('athletes.index') }}">{{ __('Atleti') }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('athletes.edit', $athlete) }}">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item route="{{ route('athletes.certificates.index', $athlete) }}">{{ $entity }}</x-backend-breadcrumb-item>
    @if($certificate)
        <x-backend-breadcrumb-item type="active">{{ $certificate->status['date'] }}</x-backend-breadcrumb-item>
    @endif
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($certificate, 'PATCH', route("athletes.certificates.update", [$athlete, $certificate]))->acceptsFiles()->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    <div class="form-group">
                        @can('viewAny', [App\Models\Certificate::class, $athlete])
                            <x-backend.buttons.return route='{{ route("athletes.certificates.index", $athlete) }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                        @endcan
                        @can('update', $certificate)
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
