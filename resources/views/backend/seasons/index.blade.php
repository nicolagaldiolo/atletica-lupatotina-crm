@extends('backend.layouts.app')

@php
    $entity = __('Vendite')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        <x-backend.buttons.create route="{{ route('seasons.create') }}" small="true" title="">
            {{ __('Aggiungi') }}
        </x-backend.buttons.create>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($years->count())
            <div class="row">
                <div class="col-auto">
                    <div class="input-group">
                        <label class="input-group-text fw-bold">{{ __('Seleziona anno') }}</label>
                        <select id="searchByYear" class="form-select">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @if($year == $searchByYear) selected @endif>{{ $year }}</option>    
                            @endforeach
                        </select>
                        <button id="dtSearch" class="btn btn-secondary" type="button">{{ __('Filtra') }}</button>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="row mt-3">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Data inizio') }}</th>
                            <th>{{ __('Data fine') }}</th>
                            <th>{{ __('Ordini') }}</th>
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
            url: '{{ route("seasons.index") }}',
            data: function(data){
                data.searchByYear = $('#searchByYear').val();
            }
        },
        order: [[2, 'desc']],
        columns: [{
                data: 'id',
                name: 'id',
                visible:false,
                searchable: false,
                orderable: false,
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'start_at',
                name: 'start_at',
                render(data, type, row, meta) {
                    console.log(data, type, row, meta)
                    /*let html = data;
                    if(!row.is_real_event){
                        html += ' <span class="badge bg-warning"><i class="fas fa-clipboard-list"></i> {{ __("Evento di segreteria") }}</span>';
                    }
                    return html;
                    */
                    return data;
                }
            },
            {
                data: 'end_at',
                name: 'end_at',
                /*searchable: false,
                render(data) {
                    return App.date(data);
                },*/
            },
            {
                data: 'orders_count',
                name: 'orders_count',
                searchable: false,
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
            }
        ],
    });

    $('#dtSearch').click(function(){
        dataTable.draw(false);
    })
</script>
@endpush
