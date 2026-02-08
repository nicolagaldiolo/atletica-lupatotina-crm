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
        <div class="card-header">
            <div class="row">
                <div class="col">
                    @include ("backend.athletes.orders.partials.action_column", ['layout' => 'form', 'disabled' => true])
                </div>
            </div>
        </div>

        <div class="card-body">
            <h5>{{ __('Dettaglio ordine') }}</h5>

            <ul class="list-group">
                <li class="list-group-item"><span>Numero Ordine: <strong>{{ $order->number }}</strong></span></li>
                <li class="list-group-item"><span>Data creazione: <strong>@date($order->created_at)</strong></span></li>
                <li class="list-group-item"><span>Stato Ordine: <strong>{{ App\Enums\OrderStatus::getDescription($order->status) }}</strong></span></li>
                <li class="list-group-item"><span>Ordinante: <strong>{{ $order->athlete->full_name }}</strong></span></li>
                <li class="list-group-item"><span>Articoli ordinati: <strong>{{ $order->quantity }}</strong></span></li>
                <li class="list-group-item">Totale: <strong>@money($order->total_amount)</strong></li>
            </ul>

            <div class="mt-3">
                <h5>{{ __('Articoli') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Articolo') }}</th>
                                <th>{{ __('Prezzo') }}</th>
                                <th>{{ __('Quantità') }}</th>
                                <th>{{ __('Importo') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Pagamento') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->rows as $row)
                                <tr>
                                    <td>
                                        @if($row->article->imageDefault)
                                            <img width="80" src="{{ $row->article->imageDefault->public_url }}">
                                        @endif
                                        {{ $row->article->name }} @if($row->variant) - {{ App\Enums\Sizes::getDescription($row->variant) }} @endif
                                    </td>
                                    <td>
                                        @money($row->article->price)
                                    </td>
                                    <td>
                                        {{ $row->quantity }}
                                    </td>
                                    <td>
                                        @money($row->total_amount)
                                    </td>
                                    <td>
                                        {{ App\Enums\OrderRowStatus::getDescription($row->status) }}
                                    </td>
                                    <td>
                                        @if($row->transaction && $row->transaction->payed_at)
                                            {{ $row->transaction->payed_at }}<br>
                                            @if($row->transaction && $row->transaction->bank_transfer) 
                                                <span class="badge text-bg-secondary">
                                                    <i class="fa-solid fa-landmark"></i> Bonifico
                                                </span>
                                            @else
                                                <span class="badge text-bg-success">
                                                    <i class="fa-solid fa-coins"></i> Contanti
                                                </span>
                                            @endif
                                        @else
                                            <i class="text-danger fa-solid fa-triangle-exclamation"></i>
                                        @endif
                                    </td>
                                </tr>    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    @include ("backend.athletes.orders.partials.action_column", ['layout' => 'form', 'disabled' => true])
                </div>
            </div>
        </div>
    </div>

@endsection

@push ('after-styles')


@endpush

@push ('after-scripts')

@endpush
