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

@section('secondary-nav')
    @include ("backend.races.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
                @can('report', App\Models\Race::class)
                    <x-backend.buttons.download route="{{ route('races.subscriptions-list', $race) }}" small="true" title="">
                        {{ __('Elenco iscritti') }}
                    </x-backend.buttons.download>
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
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Pagato il') }}
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
        ajax: '{{ route("races.athletes", $race) }}',
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
                data: 'created_at',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'custom_amount',
                render(data, type, row, meta) {
                    let html = [App.money(data)];
                    
                    if(row.voucher){
                        let amount = App.money(row.voucher.amount_calculated);

                        if(row.voucher.type == "{{ App\Enums\VoucherType::Credit }}"){
                            html.push('<span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (' + amount + ')</span>');
                        }else{
                            html.push('<span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (' + amount + ')</span>');
                        }
                    }
                    return html.join("\n");
                }
            },
            {
                data: 'payed_at',
                render(data) {
                    return App.date(data);
                },
            }
        ],
        ordering: false,
    });
</script>
@endpush
