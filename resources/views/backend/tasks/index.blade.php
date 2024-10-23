@extends('backend.layouts.app')

@php
    $entity = __('Tasks')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        {{ __('Generale') }}
                    </div>
                    <div class="card-body">
                        <a class="btn btn-primary" href="{{ route('tasks.exec', 'inspire') }}" role="button">{{ __('Inspire') }}</a>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        {{ __('Migrazioni') }}
                    </div>
                    <div class="card-body">
                        <a class="btn btn-primary" href="{{ route('tasks.exec', 'migrate') }}" role="button">{{ __('Migrate') }}</a>
                        <a class="btn btn-danger" href="{{ route('tasks.exec', 'migrate-rollback') }}" role="button">{{ __('Migrate Rollback') }}</a>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        {{ __('Manutenzione') }}
                    </div>
                    <div class="card-body">
                        <a class="btn btn-danger" href="{{ route('tasks.exec', 'setup') }}" role="button">{{ __('Setup') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection