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

@if (!count($accounts))
    <div class="card text-center">
        <div class="card-body p-5">
            <i class="fa-solid fas fa-cash-register fa-5x opacity-50"></i>
            <h5 class="mt-4">{{ __('Nessun incasso disponibile') }}</h5>
        </div>
    </div>
@else
    <div class="card">

        @can((($raceType == App\Enums\RaceType::Race) ? 'deductPaymentRace' : (($raceType == App\Enums\RaceType::Track) ? 'deductPaymentTrack' : false)), App\Models\AthleteFee::class)
            <div class="card-header">
                <x-backend.section-header>
                    <x-slot name="toolbar">
                        @can((($raceType == App\Enums\RaceType::Race) ? 'reportRace' : (($raceType == App\Enums\RaceType::Track) ? 'reportTrack' : false)), App\Models\Race::class)
                            @if(count($yearForExport))
                                <form method="POST" action="{{ route('proceeds.export', $raceType) }}">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <label class="input-group-text">{{ __('Seleziona annualità') }}</label>
                                        <select name="year" class="form-select">
                                            @foreach ($yearForExport as $year)
                                                <option @if($currentPeriod->format('Y') == $year) selected @endif value="{{ $year }}">{{ $year }}</option>    
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="nav-icon fas fa-cash-register"></i> {{ __('Scarica report incassi') }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endcan
                    </x-slot>
                </x-backend.section-header>
            </div>
        @endcan

        <div class="card-body">
            @if (count($accounts))
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach ($accounts as $account)
                        <button class="nav-link @if($loop->first) active @endif" id="account-{{$account->id}}-tab" data-coreui-toggle="tab" data-coreui-target="#account-{{$account->id}}" type="button" role="tab" aria-controls="account-{{$account->id}}" aria-selected="true">
                            <i class="nav-icon fa-solid fa-user"></i>
                            {{$account->name}}
                        </button>
                    @endforeach
                    <button class="nav-link id="account-bank-tab" data-coreui-toggle="tab" data-coreui-target="#account-bank" type="button" role="tab" aria-controls="account-bank" aria-selected="true">
                        <i class="fa-solid fa-landmark"></i>
                        {{ __('Bonifici') }}
                    </button>
                </div>
                <div class="tab-content mt-3" id="nav-tabContent">
                    @foreach ($accounts as $account)
                        @include('backend.proceeds.partials.tab_content', ['user_id' => $account->id, 'is_first' => $loop->first])
                    @endforeach
                    @include('backend.proceeds.partials.tab_content', ['user_id' => 'bank', 'is_first' => false])
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
@endpush
