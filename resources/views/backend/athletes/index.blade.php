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

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $entity }}
            <x-slot name="toolbar">
                @can('create', App\Models\Athlete::class)
                    <x-buttons.create route="{{ route('backend.athletes.create') }}" small="true" title="">
                        {{ __('Aggiungi') }}
                    </x-buttons.create>
                @endcan

                @can('restore', App\Models\Athlete::class)
                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href='{{ route("backend.athletes.trashed") }}'>
                                    <i class="fas fa-archive"></i> {{ __('Archivio') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                @endcan
            </x-slot>
        </x-backend.section-header>
    </div>
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
                                Name
                            </th>
                            <th>
                                Da pagare
                            </th>
                            <th class="text-end">
                                Action
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
        ajax: '{{ route("backend.athletes.index") }}',
        order: [[ 1, "asc" ]],
        columns: [
            {
                data: 'id',
                name: 'id',
                //visible: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'fees_to_pay',
                render(data) {
                    if(data && data.length){
                        let amount = data.reduce((i, item) => i+item.amount, 0);
                        return '<span class="badge text-bg-primary">' + App.money(amount) + ' (' + data.length + ')</span>';
                    }
                    return null;
                },
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
