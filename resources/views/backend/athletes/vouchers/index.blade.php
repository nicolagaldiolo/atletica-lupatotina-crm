@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("athletes.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.secondary_nav")
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
                <x-buttons.create route="{{ route('athletes.vouchers.create', $athlete) }}" small="true" title="">
                    {{ __('Crea Voucher') }}
                </x-buttons.create>
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
                                {{ __('Tipo') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Creato il') }}
                            </th>
                            <th>
                                {{ __('Usato il') }}
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
        ajax: '{{ route("athletes.vouchers.index", $athlete) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'name'
            },
            {
                data: 'type',
                name: 'type',
                render(data) {
                    console.log("data", data);
                    if(data == "{{ App\Enums\VoucherType::Credit }}"){
                        return '<span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }}</span>';
                    }else{
                        return '<span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }}</span>';
                    }

                    return null;
                }
            },
            {
                data: 'amount',
                render(data) {
                    return App.money(data);
                }
            },
            {
                data: 'created_at'
            },
            {
                data: 'used_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        ordering: false,
    });
</script>
@endpush

