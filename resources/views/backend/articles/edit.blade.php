@extends('backend.layouts.app')

@php
    $entity = __('Abbigliamento');
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $article->avatar }}">
@endsection
@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $article->name }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')
{{ html()->modelForm($article, 'PATCH', route("articles.update", $article))->class('form')->open() }}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $article)
                    <x-backend.buttons.delete route='{{ route("articles.destroy", $article) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('viewAny', App\Models\Article::class)
                        <x-backend.buttons.return route='{{ route("articles.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $article)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
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
</div>

{{ html()->form()->close() }}

@endsection
