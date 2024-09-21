@extends('backend.layouts.app')

@php
    $entity = __('Atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can('create', App\Models\Athlete::class)
            <x-buttons.create route="{{ route('athletes.create') }}" small="true" title="">
                {{ __('Aggiungi') }}
            </x-buttons.create>
        @endcan
        
        {{--
        @can('restore', App\Models\Athlete::class)
            <div class="btn-group">
                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href='{{ route("athletes.trashed") }}'>
                            <i class="fas fa-archive"></i> {{ __('Archivio') }}
                        </a>
                    </li>
                </ul>
            </div>
        @endcan
        --}}
    </div>
    
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Nome') }}
                            </th>
                            <th>
                                {{ __('Certificato') }}
                            </th>
                            <th>
                                {{ __('Iscrizioni') }}
                            </th>
                            <th>
                                {{ __('Da pagare') }}
                            </th>
                            <th>
                                {{ __('Utente registrato') }}
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
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="module" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="module">
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: "{{ route('athletes.index') }}",
            type: "GET",
            "datatype": 'json'
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
                name: 'name'
            },
            {
                data: 'certificate',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-' + data.status.status_class + '">' + data.status.date + ' (' + data.status.date_diff + ')</span>';
                    }

                    return null;
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'fees_count',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-primary">' + data + '</span>';
                    }
                    return null;
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'fees_to_pay',
                render(data) {
                    if(data && data.length){
                        let amount = data.reduce((i, item) => i+item.amount, 0);
                        return '<span class="badge text-bg-danger">' + App.money(amount) + ' (' + data.length + ')</span>';
                    }
                    return null;
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'user',
                render(data) {
                    return data ? '<i class="fas fa-user"></i> (id:' + data.id + ')' : '<i class="fas fa-user-slash text-secondary"></i>';
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>
@endpush
