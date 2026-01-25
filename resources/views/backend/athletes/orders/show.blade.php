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
    <x-backend-breadcrumb-item type="active">{{ $order->created_at }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    
                    <div class="float-end">
                        {{--@can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)--}}
                            <x-backend.buttons.return route='{{ route("athletes.orders.index", [$athlete]) }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                        {{--@endcan--}}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <h3>
                        {{ __('Dettagli ordine') }}
                    </h3>
                    <ul>
                        <li><span>Data creazione {{ $order->created_at }}</span></li>
                        <li><span>Stato Ordine: Consegnato</span></li>
                        <li><span>Ordinante: {{ $order->athlete->full_name }}</span></li>
                        <li><span>Articoli ordinati {{ $order->quantity }}</span></li>
                        <li><span>Totale {{ $order->total_amount }}</span></li>
                    </ul>
                </div>
                <div class="col-lg-6">

                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>{{ __('Articolo') }}</th>
                                <th>{{ __('Prezzo') }}</th>
                                <th>{{ __('Quantità') }}</th>
                                <th>{{ __('Importo') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->rows as $row)
                                <tr>
                                    <td>
                                        {{ $row->article->name }} @if($row->variant) - {{ App\Enums\Sizes::getDescription($row->variant) }} @endif
                                    </td>
                                    <td>
                                        {{ $row->article->price }} €
                                    </td>
                                    <td>
                                        {{ $row->quantity }}
                                    </td>
                                    <td>
                                        {{ $row->total_amount }}
                                    </td>
                                </tr>    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push ('after-styles')


@endpush

@push ('after-scripts')

@endpush
