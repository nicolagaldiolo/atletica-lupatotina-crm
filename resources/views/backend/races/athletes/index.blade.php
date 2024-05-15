@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item>{{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $race->name }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            {{ $entity }}
            <x-slot name="toolbar">
                @can('restore', App\Models\AthleteRace::class)
                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href='{{ route("backend.races.trashed") }}'>
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
                                {{ __('Nome') }}
                            </th>
                            <th>
                                {{ __('Quota') }}
                            </th>
                            <th>
                                {{ __('Iscritto il') }}
                            </th>
                            <th>
                                {{ __('Pagato il') }}
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
        ajax: '{{ route("backend.races.athletes", $race) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'athlete',
                render(data) {
                    return data ? data.fullname : null;
                },
            },
            {
                data: 'fee',
                render(data) {
                    return data ? data.name + ' (' + App.money(data.amount) + ')' : null;
                },
            },
            {
                data: 'created_at'
            },
            {
                data: 'payed_at'
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
