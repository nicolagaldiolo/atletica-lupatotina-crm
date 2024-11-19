@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection
@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.partials.form", ['disabled' => true])
            </div>
        </div>
    </div>
</div>
@endsection
