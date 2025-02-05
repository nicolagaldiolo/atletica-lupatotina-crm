@extends('backend.layouts.app')

@php
    $entity = __('Incassi')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')

<div class="card">
    {{--
    @can('deductPayment', App\Models\AthleteFee::class)
        <div class="card-header">
            <x-backend.section-header>
                <x-slot name="toolbar">
                    @can('report', App\Models\Race::class)
                        <div class="input-group input-group-sm">
                            <label class="input-group-text">{{ __('Seleziona anno') }}</label>
                            <select class="form-select">
                                <option>2024</option>
                                <option>2025</option>
                            </select>
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-download"></i> {{ __('Download report') }}
                            </button>
                        </div>
                    @endcan
                </x-slot>
            </x-backend.section-header>
        </div>
    @endcan
    --}}

    <div class="card-body">
        @if (count($accounts))
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @foreach ($accounts as $account)
                    <button class="nav-link @if($loop->first) active @endif" id="account-{{$account->id}}-tab" data-coreui-toggle="tab" data-coreui-target="#account-{{$account->id}}" type="button" role="tab" aria-controls="account-{{$account->id}}" aria-selected="true">
                        {{$account->name}}
                    </button>
                @endforeach
            </div>
            <div class="tab-content mt-3" id="nav-tabContent">
                @foreach ($accounts as $account)
                    @include('backend.proceeds.partials.tab_content')
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
@endpush
