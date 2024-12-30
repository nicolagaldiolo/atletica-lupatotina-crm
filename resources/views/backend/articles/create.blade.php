@extends('backend.layouts.app')

@php
    $entity = __('Atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo articolo') }}</x-backend-breadcrumb-item>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("articles.store"))->class('form')->open() }}
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Article::class)
                                <x-backend.buttons.return route='{{ route("articles.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Article::class)
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
                    @include ("backend.articles.partials.form")
                </div>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>

@endsection
