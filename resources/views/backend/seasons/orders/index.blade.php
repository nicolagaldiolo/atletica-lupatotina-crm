@extends('backend.layouts.app')

@php
    $entity = __('Stagioni')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active" canurl="" route="{{ route('seasons.index', $season) }}">{{ $season->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Ordini') }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    @can('viewAny', App\Models\Season::class)
                        <x-backend.buttons.return route='{{ route("seasons.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        
        <h5>{{ __('Ordini ordinati') }}</h5>
            <table id="datatable" class="table table-bordered table-responsive-sm">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            {{ __('Numero') }}
                        </th>
                        <th>
                            {{ __('Socio') }}
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

        
        
            <h5 class="mt-5">{{ __('Articoli ordinati') }}</h5>
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
                data: 'number',
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
                orderable: false,
                searchable: false
            },
            {
                data: 'quantity',
                orderable: false,
                searchable: false
            },
            {
                data: 'total_amount',
                render(data) {
                    return App.money(data);
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'status',
                orderable: false,
                searchable: false
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
                data: 'article_name',
                render(data, type, row, meta) {
                    let html = [];
                    
                    if(row.image){
                        html.push("<img width='50' src='" + row.image + "' />");
                    }

                    html.push(data);

                    return html.join(" ");
                },
            },
            {
                data: 'size_name',
            },
            
            {
                data: 'quantity'
            }
        ]
    });
</script>
@endpush
