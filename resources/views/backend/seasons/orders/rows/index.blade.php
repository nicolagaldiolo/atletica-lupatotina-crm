@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection
{{-- 
@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', [$race->type, $race]) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Quote') }}</x-backend-breadcrumb-item>
@endsection
--}}

@section('secondary-nav')
    {{--@include ("backend.races.partials.action_column", ['layout' => 'nav'])--}}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
            </x-slot>
        </x-backend.section-header>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="card" id="massUpdateForm" style="display: none;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                Gestisci righe d'ordine
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ html()->modelForm($order, 'PATCH', 'seasons.orders.update', [$season, $order])->class('form')->open() }}
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <div class="form-group mb-3">
                                        <label for="status">{{ __('Gestisci consegna') }}</label>
                                        
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input form-item" type="checkbox" name="massive_enable_status" value="1">
                                        </div>

                                        <select class="form-select" name="status" disabled>
                                            @foreach (App\Enums\OrderRowStatus::asSelectArray() as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                 <label for="status">{{ __('Gestisci Pagamento') }}</label>
                                        
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input form-item" type="checkbox" name="massive_enable_payment" value="1">
                                        </div>
                                
                                <div class="col-12 col-sm-2">
                                    <div class="form-group mb-3">
                                        <label for="payed">{{ __('Pagato') }}</label>
                                        <span class="text-danger">*</span>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input form-item" type="checkbox" name="payed" value="1" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-2">
                                    <div class="form-group mb-3">
                                        <label for="bank_transfer">{{ __('Pagato con bonifico') }}</label>
                                        <span class="text-danger">*</span>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input form-item input-switch" type="checkbox" name="bank_transfer" value="1" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="form-group mb-3 cashed_by_container">
                                        <label for="cashed_by">{{ __('Esattore') }}</label>
                                        <span class="text-danger">*</span>
                                        <select class="form-select" name="cashed_by" disabled>
                                            @foreach ($accountants as $accountant)
                                                <option @if(Auth::id() == $accountant->id) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-3">
                                    <button id="massUpdate" class="btn btn-light" disabled>
                                        <i class="fas fa-edit pr-4"></i> {{ __('Aggiorna selezionati') }}
                                    </button>
                                </div>
                            </div>
                        {{ html()->form()->close() }}
                    </div>
                </div>


                <table id="datatable" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th><input type="checkbox" class="selectAll" name="selectAll"></th>
                            <th>
                                {{ __('Articolo') }}
                            </th>
                            <th>
                                {{ __('Variante') }}
                            </th>
                            <th>
                                {{ __('Quantità') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Totale') }}
                            </th>
                            <th>
                                {{ __('Stato') }}
                            </th>
                            <th>
                                {{ __('Pagamento') }}
                            </th>
                            <th>
                                {{ __('Pagato a') }}
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

    function dataTableGetSelectedRows(dt){
        return dt.rows({ selected: true }).data().toArray();
    }

    var massUpdateForm = $('#massUpdateForm');
    var massUpdateBtn = $('#massUpdate');

    var dataTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("seasons.orders.edit", [$season, $order]) }}',
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
            },
            {
                data: null,
                defaultContent: '',
                orderable: false,
                className: 'select-checkbox dt-head-center'
            },
            {
                data: 'article.name',
                render(data, type, row, meta) {

                    console.log(row);

                    let html = [];
                    
                    if(row.article && row.article.image_default && row.article.image_default.public_url){
                        html.push("<img width='50' src='" + row.article.image_default.public_url + "' />");
                    }

                    html.push(data);

                    return html.join(" ");
                }
            },
            {
                data: 'variant',
            },
            {
                data: 'quantity',
            },
            {
                data: 'amount'
            },
            {
                data: 'total_amount',
            },
            {
                data: 'status',
            },
            {
                data: 'is_payed',
                orderable: false,
                searchable: false,
                render(data, type, row, meta) {
                    if(row.transaction && row.transaction.payed_at){
                        return [
                            App.date(row.transaction.payed_at),
                            (row.transaction && row.transaction.bank_transfer) ? '<span class="badge text-bg-secondary"><i class="fa-solid fa-landmark"></i> Bonifico</span>' : '<span class="badge text-bg-success"><i class="fa-solid fa-coins"></i> Contanti</span>'
                        ].join("<br>");
                    }else{
                        return '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                    }
                },
            },
            {
                data: null,
                render(data) {
                    return data && data.transaction && data.transaction.cashed ? data.transaction.cashed.name : null;
                },
                orderable: false,
                searchable: false
            },
        ]
    });

    dataTable.on('select deselect', function ( e, dt, type, indexes ) {
        if ( type === 'row' ) {
            if(dataTableGetSelectedRows(dataTable).length){
                massUpdateForm.show();
            }else{
                massUpdateForm.hide();
            }
        }
    });

    $(".selectAll").on( "click", function(e) {
        if ($(this).is( ":checked" )) {
            dataTable.rows().select();
        } else {
            dataTable.rows().deselect();
        }
    });

    const massive_enable_status = $('[name="massive_enable_status"]');
    const massive_enable_payment = $('[name="massive_enable_payment"]');

    const form = document.querySelector('#massUpdateForm');
            
    const status = $('[name="status"]');
    const payed = $('[name="payed"]');
    const bank_transfer = $('[name="bank_transfer"]');
    const cashed_by = $('[name="cashed_by"]');

    let massUpdateBtnHandler = function(){
        console.log("Eccomi");
        massUpdateBtn.prop('disabled', !(massive_enable_status.is(':checked') || massive_enable_payment.is(':checked')));
    }

    massive_enable_status.on('change', function () {
        const self_checked = $(this).is(':checked');
        status.prop('disabled', !self_checked);

        massUpdateBtnHandler();
    });

    massive_enable_payment.on('change', function () {
        const self_checked = $(this).is(':checked');

        payed.prop('disabled', !self_checked);

        massUpdateBtnHandler();
    });

    payed.on('change', function () {
        const self_checked = $(this).is(':checked');

        bank_transfer.prop('disabled', !self_checked);
        cashed_by.prop('disabled', !self_checked);
        
        massUpdateBtnHandler();
    });

    bank_transfer.on('change', function () {
        const self_checked = $(this).is(':checked');
        cashed_by.prop('disabled', self_checked);
        
        massUpdateBtnHandler();
    });

    massUpdateBtn.click( function (e) {
        e.preventDefault();
        let selectedRows = dataTableGetSelectedRows(dataTable);

        if(selectedRows.length){
            let currentIds = selectedRows.map(function(item){
                return item.id;
            });

            

            let payload = {
                ids: currentIds,
                massive_enable_status: (massive_enable_status.is(':checked') ? 1 : 0),
                status: status.val(),
                massive_enable_payment: (massive_enable_payment.is(':checked') ? 1 : 0),
                payed: payed.is(':checked') ? 1 : 0,
                bank_transfer: bank_transfer.is(':checked') ? 1 : 0,
                cashed_by: cashed_by.val()
            };

            console.log(payload);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('seasons.orders.update', [$season, $order]) }}",
                type: 'PATCH',
                data: payload,
            }).done(function(data) {
                console.log(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("fail");
                //iziToast.error({
                //    message: jqXHR.responseJSON ? jqXHR.responseJSON.message : textStatus
                //});
                
            }).always(function() {
                console.log("always");
                //dataTable.draw(false);
                window.location.reload();
            });

            //window.location.href = "";
        }else{
            alert("Nessuna riga selezionata");
        }
    });

</script>
@endpush
