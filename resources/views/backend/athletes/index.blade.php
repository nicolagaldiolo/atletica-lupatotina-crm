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
                {{ __('Aggiungi') }}
            </x-backend.buttons.create>
        @endcan
    </div>
    
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-auto">
                <div class="input-group">
                    <label class="input-group-text fw-bold">{{ __('Filtra') }}</label>
                    <select id="searchByActive" class="form-select">
                        <option value="1" @if($searchByActive == 1) selected @endif>{{ __('Attivi') }}</option>
                        <option value="0"@if($searchByActive == 0) selected @endif>{{ __('Non attivi') }}</option>
                        <option value="-1" @if($searchByActive == -1) selected @endif>{{ __('Tutti') }}</option>
                    </select>
                    <button id="dtSearch" class="btn btn-secondary" type="button">{{ __('Filtra') }}</button>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <table id="datatable" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Nome') }}
                            </th>
                            <th>
                                {{ __('Attivo') }}
                            </th>
                            <th>
                                {{ __('Fidal') }}
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
                                {{ __('Utente') }}
                            </th>
                            <th>
                                {{ __('Invito') }}
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
    let dataTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route("athletes.index") }}',
            data: function(data){
                data.searchByActive = $('#searchByActive').val();
            }
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
                name: 'name',
                render(data, type, row, meta) {
                    if(data){
                        var html = [
                            '<img class="avatar me-2" src="' + row.avatar + '">',
                            data
                        ];
                        return html.join("");
                    }

                    return null;
                },
            },
            {
                data: 'is_active',
                render(data, type, row, meta) {
                    if(data){
                        return '<i class="fa-solid fa-user-check"></i>';
                    }else{
                        return '<i class="fa-solid fa-ban"></i>';
                    }
                },
            },
            {
                data: 'registration_number',
                render(data, type, row, meta) {
                    return data ? data : null;
                },
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
                searchable: false
            },
            {
                data: null,
                render(data, type, row, meta) {
                    
                    var html = [];

                    @canany(['subscribeRace', 'registerPaymentRace'], App\Models\AthleteFee::class)
                        let race_fees_to_pay = row.fees_to_pay.filter(item => item.race.type === 'race');
                        if(race_fees_to_pay.length){
                            let amount_race = race_fees_to_pay.reduce((i, item) => i+item.athletefee.custom_amount, 0);
                            html.push('<span class="badge text-bg-danger"><i class="fa-solid fa-flag-checkered"></i> ' + App.money(amount_race) + ' (' + race_fees_to_pay.length + ')</span>');
                        }
                    @endcanany

                    @canany(['subscribeTrack', 'registerPaymentTrack'], App\Models\AthleteFee::class)
                        let track_fees_to_pay = row.fees_to_pay.filter(item => item.race.type === 'track');
                        if(track_fees_to_pay.length){
                            let amount_track = track_fees_to_pay.reduce((i, item) => i+item.athletefee.custom_amount, 0);
                            html.push('<span class="badge text-bg-danger"><i class="fa-solid fa-ring"></i> ' + App.money(amount_track) + ' (' + track_fees_to_pay.length + ')</span>');
                        }
                    @endcanany

                    return html.join("</br>");
                },
                searchable: false,
            },
            {
                data: 'user',
                render(data) {
                    return data ? '<i class="fas fa-user"></i> (id:' + data.id + ')' : '<i class="fas fa-user-slash text-secondary"></i>';
                },
                searchable: false,
                orderable: false,
                visible: "{{ auth()->user()->can('invite', App\Models\Athlete::class) }}"
            },
            {
                data: 'invited_at',
                visible: "{{ auth()->user()->can('invite', App\Models\Athlete::class) }}",
                render(data) {
                    return data ? App.date(data, true) : null;
                },
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        createdRow: function( row, data, dataIndex){
            if(!data.is_active){
                $(row).addClass('opacity-50');
            }
        }
    });

    $('#dtSearch').click(function(){
        dataTable.draw(false);
    })
</script>
@endpush
