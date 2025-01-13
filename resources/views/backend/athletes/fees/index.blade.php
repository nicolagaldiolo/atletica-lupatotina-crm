@extends('backend.layouts.app')

@php
    $entity = __('Iscrizioni');
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canrul="{{ Auth::user()->can('update', $athlete) }}" route='{{route("athletes.edit", $athlete)}}'>{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
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
                                {{ __('Quota') }}
                            </th>
                            <th>
                                {{ __('Data iscrizione') }}
                            </th>
                            <th>
                                {{ __('Iscritto da') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Pagamento') }}
                            </th>
                            <th>
                                {{ __('Pagato a') }}
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
        ajax: '{{ route("athletes.fees.index", [$athlete, "raceType" => $raceType]) }}',
        order: [[3, 'desc']],
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
                orderable: false,
                searchable: false
            },
            {
                data: 'fee.race.name',
            },
            {
                data: 'fee',
                render(data) {
                    return data ? data.name + ' (' + App.money(data.amount) + ')' : null;
                }
            },
            {
                data: 'created_at',
                render(data) {
                    return data ? App.date(data) : null
                }
            },
            {
                data: 'owner',
                render(data) {
                    return data ? data.name : null;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'custom_amount',
                render(data, type, row, meta) {

                    let html = [
                        App.money(data)
                    ];
                    
                    if(row.voucher){
                        let amount = App.money(row.voucher.amount_calculated);

                        if(row.voucher.type == "{{ App\Enums\VoucherType::Credit }}"){
                            html.push('<span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (' + amount + ')</span>');
                        }else{
                            html.push('<span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (' + amount + ')</span>');
                        }
                    }
                    return html.join("<br>");
                }
            },
            {
                data: 'bank_transfer',
                render(data, type, row, meta) {
                    if(row.payed_at){
                        return [
                            App.date(row.payed_at),
                            data ? '<span class="badge text-bg-secondary"><i class="fa-solid fa-landmark"></i> Bonifico</span>' : '<span class="badge text-bg-success"><i class="fa-solid fa-coins"></i> Contanti</span>'
                        ].join("<br>");
                    }else{
                        return '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                    }
                }
            },
            {
                data: 'cashed',
                render(data) {
                    return data ? data.name : null;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        createdRow: function( row, data, dataIndex){
            if(!data.payed_at){
                $(row).addClass('bg-danger');
                $(row).css('--cui-bg-opacity', '.5');
            }
        }
    });
</script>
@endpush

