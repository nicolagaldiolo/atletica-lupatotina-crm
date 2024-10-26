@extends('backend.layouts.app')

@php
    $entity = __('Certiticati')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canrul="{{ Auth::user()->can('edit', $athlete) }}" route='{{route("athletes.edit", $athlete)}}'>{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">@date($certificate->expires_on)</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($certificate, 'PATCH', route("athletes.certificates.update", [$athlete, $certificate]))->acceptsFiles()->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $certificate)
                    <x-backend.buttons.delete route='{{ route("athletes.certificates.destroy", [$athlete, $certificate]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    <div class="form-group">
                        @can('viewAny', [App\Models\Certificate::class, $athlete])
                            <x-backend.buttons.return route='{{ route("athletes.certificates.index", $athlete) }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
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
