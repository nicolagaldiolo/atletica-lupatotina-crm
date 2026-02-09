@extends('backend.layouts.app')

@php
    $entity = __('Taglie');
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
@endsection
@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $size->name }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')
{{ html()->modelForm($size, 'PATCH', route("sizes.update", $size))->class('form')->open() }}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $size)
                    <x-backend.buttons.delete route='{{ route("sizes.destroy", $size) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('viewAny', App\Models\Article::class)
                        <x-backend.buttons.return route='{{ route("articles.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $size)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.sizes.partials.form")
            </div>
        </div>
    </div>
</div>

{{ html()->form()->close() }}

@endsection
