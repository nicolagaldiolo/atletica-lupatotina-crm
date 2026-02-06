@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection
{{-- 
@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', [$race->type, $race]) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Quote') }}</x-backend-breadcrumb-item>
@endsection
--}}

@section('secondary-nav')
    {{--@include ("backend.races.partials.action_column", ['layout' => 'nav'])--}}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
            </x-slot>
        </x-backend.section-header>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h3>
                    {{ __('Elenco ordini') }}
                </h3>
                <table id="datatable" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Ordinante') }}
                            </th>
                            <th>
                                {{ __('Data ordine') }}
                            </th>
                            <th>
                                {{ __('Articoli') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Stato') }}
                            </th>
                            <th>
                                {{ __('Pagamento') }}
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-4">
                <h3>
                    {{ __('Articoli ordinati') }}
                </h3>

                <table id="datatable_articles_detail" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Articolo') }}
                            </th>
                            <th>
                                {{ __('Taglia') }}
                            </th>
                            <th>
                                {{ __('Quantità') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

<script type="module">
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("seasons.orders.index", $season) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
            },
            {
                data: 'fullname',
                name: 'fullname',
            },
            {
                data: 'created_at',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'quantity'
            },
            {
                data: 'total_amount',
                render(data) {
                    return App.money(data);
                },
            },
            {
                data: 'status'
            },
            {
                data: 'payment_status',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });

    $('#datatable_articles_detail').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("seasons.products.index", $season) }}',
        columns: [
            {
                data: 'article_id',
                visible: false,
            },

            {
                data: 'name',
                render(data, type, row, meta) {
                    let html = [];
                    
                    if(row.image){
                        html.push("<img width='50' src='" + row.image + "' />");
                    }

                    html.push(data);

                    return html.join(" ");
                },
                //visible: false,
            },

            {
                data: 'variant',
                orderable: false,
                searchable: false
            },
            
            {
                data: 'quantity',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>
@endpush
