@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.athletes.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo atleta') }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">{{ $athlete->fullname }}</a>
      <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route("backend.athletes.edit", $athlete) }}">{{ __('Generale') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route("backend.athletes.edit", $athlete) }}">{{ __('Gare') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route("backend.athletes.edit", $athlete) }}">{{ __('Pagamenti') }}</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

{{ html()->modelForm($athlete, 'PATCH', route("backend.athletes.update", $athlete))->class('form')->open() }}
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $athlete->fullname }}
            <x-slot name="toolbar">
                @can('viewAny', App\Models\Athlete::class)
                    <x-backend.buttons.return route='{{ route("backend.athletes.index") }}' icon="fas fa-reply" small="true" />
                @endcan
                @can('delete', $athlete)
                    <x-backend.buttons.delete route='{{ route("backend.athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.partials.form", ['disabled' => false])
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    @can('viewAny', App\Models\Athlete::class)
                        <x-backend.buttons.return route='{{ route("backend.athletes.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $athlete)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
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
