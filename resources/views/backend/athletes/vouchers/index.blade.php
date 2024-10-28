@extends('backend.layouts.app')

@php
    $entity = __('Voucher');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canrul="{{ Auth::user()->can('edit', $athlete) }}" route='{{route("athletes.edit", $athlete)}}'>{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    @can('create', App\Models\Voucher::class)
        <div class="card-header">
            <x-backend.section-header>
                <x-slot name="toolbar">
                    <x-buttons.create route="{{ route('athletes.vouchers.create', $athlete) }}" small="true" title="">
                        {{ __('Crea Voucher') }}
                    </x-buttons.create>
                </x-slot>
            </x-backend.section-header>
        </div>
    @endcan
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
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Creato il') }}
                            </th>
                            <th>
                                {{ __('Usato il') }}
                            </th>
                            <th class="text-end">
                                &nbsp;
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
        ajax: '{{ route("athletes.vouchers.index", $athlete) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false,
                orderable: false,
            },
            {
                data: 'name'
            },
            {
                data: null,
                render(data, type, row, meta) {
                    
                    let amount = App.money(row.amount_calculated);

                    if(row.type == "{{ App\Enums\VoucherType::Credit }}"){
                        return '<span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (' + amount + ')</span>';
                    }else{
                        return '<span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (' + amount + ')</span>';
                    }
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'created_at',
                render(data) {
                    return App.date(data);
                },
                searchable: false,
                orderable: false,
            },
            {
                data: 'used_at',
                render(data, type, row, meta) {
                    let html = [
                        App.date(data)
                    ];
                    
                    if(row.athletefee){
                        html.push("<span class='badge text-bg-secondary'>(" + row.athletefee.fee.race.name + " | " + row.athletefee.fee.name + ")</span>");
                    }

                    return html.join(" ");
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
        ],
    });
</script>
@endpush

