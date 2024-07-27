@extends('backend.layouts.app')

@php
    $entity = __('Le mie gare')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>{{ __('Gara') }}</th>
                            <th>{{ __('Quota') }}</th>
                            <th>{{ __('Importo') }}</th>
                            <th>{{ __('Pagato il') }}</th>
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
        ajax: '{{ route("myraces.index") }}',
        //order: [[2, 'asc']],
        columns: [
            {
                data: null,
                render(data) {
                    return data && data.race && data.race.name ? data.race.name : null;
                },
            },
            {
                data: 'name',
            },
            {
                data: 'amount',
                render(data) {
                    return data ? App.money(data) : null;
                }
            },
            {
                data: 'payed_at',
                render(data) {
                    return data ? '<i class="fa-solid fa-coins"></i> (' + data + ')' : '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                }
            },
        ],
        ordering: false,
        createdRow: function( row, data, dataIndex){
            if(!data.payed_at){
                $(row).addClass('table-danger');
            }
        }
    });
</script>
@endpush
