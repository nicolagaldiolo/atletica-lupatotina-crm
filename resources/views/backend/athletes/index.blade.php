@extends('backend.layouts.app')

@php
    $entity = __('Atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can('create', App\Models\Athlete::class)
            <x-backend.buttons.create route="{{ route('athletes.create') }}" small="true" title="">
                {{ __('Aggiungi nuovo') }}
            </x-backend.buttons.create>
        @endcan
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
                                {{ __('Vouchers/Penalità') }}
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
@endpush

@push ('after-scripts')

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
                searchable: false
            },
            {
                data: 'fees_count',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-secondary">' + data + '</span>';
                    }
                    return null;
                },
                searchable: false
            },
            {
                data: 'fees_to_pay_count',
                render(data, type, row, meta) {
                    if(data){
                        let amount = row.fees_to_pay.reduce((i, item) => i+item.athletefee.custom_amount, 0);
                        return '<span class="badge text-bg-danger">' + App.money(amount) + ' (' + data + ')</span>';
                    }
                    return null;
                },
                searchable: false,
            },
            {
                data: 'vouchers_count',
                searchable: false
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
