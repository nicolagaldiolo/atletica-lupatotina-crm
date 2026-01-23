@extends('backend.layouts.app')

@php
    $entity = __('Abbigliamento')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can('create', App\Models\Article::class)
            <x-backend.buttons.create-dropdown small="true" title="" label="{{ __('Aggiungi nuovo') }}">
                @foreach (App\Enums\ArticleType::asArray() as $type)
                    <li><a class="dropdown-item" href="{{ route('articles.create', ['type' => $type]) }}">{{ ucfirst($type) }}</a></li>
                @endforeach
            </x-backend.buttons.create-dropdown>
        @endcan
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Prodotto') }}
                            </th>
                            <th>
                                {{ __('Prezzo') }}
                            </th>
                            <th>
                                {{ __('Quantità') }}
                            </th>
                            <th>
                                {{ __('Attivo') }}
                            </th>
                            <th>&nbsp;</th>
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
    let dataTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route("articles.index") }}'
        },
        order: [[ 1, "asc" ]],
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false,
                orderable: false,
            },
            {
                data: 'name',
            },
            {
                data: 'price',
                render(data) {
                    return App.money(data);
                },
            },
            {
                data: null,
                render(data) {
                    var html = null;
                    switch(data.type){
                        case '{{ App\Enums\ArticleType::Simple }}':
                            html = data.quantity;
                            break;
                        case '{{ App\Enums\ArticleType::Variants }}':

                            var variants = Object.entries(data.variants).reduce((arr, [key, val]) => {
                                
                                arr.push('<li>' + key + ': ' + val + '</li>')
                                return arr;
                            }, []);

                            html = (variants.length ? ('<ul>' + variants.join('') + '</ul>') : null);
                            break;
                        default:
                            html = 0;
                    }
                    return html;
                },
            },
            {
                data: 'is_active',
                render(data, type, row, meta) {
                    if(data){
                        return '<i class="fa-solid fa-check"></i>';
                    }else{
                        return '<i class="fa-solid fa-ban"></i>';
                    }
                },
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });
</script>
@endpush
