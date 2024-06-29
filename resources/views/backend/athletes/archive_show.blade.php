@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("athletes.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo atleta') }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $athlete->fullname }}
            <x-slot name="toolbar">
                @can('viewAny', App\Models\Athlete::class)
                    <x-backend.buttons.return route='{{ route("athletes.trashed") }}' icon="fas fa-reply" small="true" />
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.partials.form", ['disabled' => true])
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    @can('restore', $athlete)
                    <x-backend.buttons.restore route='{{ route("athletes.restore", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}">{{ __('Reintegra') }} </x-backend.buttons.restore>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <small class="float-end text-muted">
        Updated: {{$athlete->updated_at->diffForHumans()}},
        Created at: {{$athlete->created_at->isoFormat('LLLL')}}
    </small>
</div>

@endsection
