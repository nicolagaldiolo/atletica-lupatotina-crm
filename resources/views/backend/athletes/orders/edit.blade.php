@extends('backend.layouts.app')

@php
    $entity = __('Ordini')
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="true" route="#">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $order->number }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    {{ html()->modelForm($order, 'PATCH', route("athletes.orders.update", [$athlete, $order]))->class('form')->open() }}
    <div class="card-header">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.orders.partials.action_column", ['layout' => 'form', 'disabled' => false])
            </div>
        </div>
    </div>

    <div class="card-body">
        @include ("backend.athletes.orders.partials.form")
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                @include ("backend.athletes.orders.partials.action_column", ['layout' => 'form', 'disabled' => false])
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
