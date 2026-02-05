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
                
                <div class="card" id="massUpdateForm" {{--style="display: none;"--}}>
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                Gestisci righe d'ordine
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row mt-4">
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group mb-3">
                                            <label for="status">{{ __('Status') }}</label>
                                            
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input form-item" type="checkbox">
                                            </div>

                                            <select class="form-select" name="status">
                                                @foreach (App\Enums\OrderRowStatus::asSelectArray() as $key => $value)
                                                    <option value="{{ $key }}">{{ __($value) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- 
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group mb-3">
                                            <label for="payed">{{ __('Pagato') }}</label>
                                            <span class="text-danger">*</span>
                                            <div class="form-check form-switch form-switch-lg">
                                                <input name="payed" class="form-item" type="hidden" checked value="0">
                                                <input class="form-check-input form-item" type="checkbox" name="payed" value="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-2">
                                        <div class="form-group mb-3">
                                            <label for="bank_transfer">{{ __('Pagato con bonifico') }}</label>
                                            <span class="text-danger">*</span>
                                            <div class="form-check form-switch form-switch-lg">
                                                <input name="bank_transfer" class="form-item" type="hidden" checked value="0">
                                                <input class="form-check-input form-item input-switch" type="checkbox" name="bank_transfer" value="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-3">
                                        <div class="form-group mb-3 cashed_by_container">
                                            <label for="bank_transfer">{{ __('Esattore') }}</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-select" name="cashed_by">
                                                @foreach ($accountants as $accountant)
                                                    <option @if(Auth::id() == $accountant->id) selected @endif value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    --}}
                                    
                                    <div class="col-12 col-sm-3">
                                        <button id="massUpdate" {{--style="display: none;"--}} class="btn btn-light" data-toggle="tooltip" data-placement="top" title="{{ __('Aggiorna articoli') }}">
                                            <i class="fas fa-edit pr-4"></i> {{ __('Aggiorna selezionati') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
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
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
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

    massUpdateBtn.click( function (e) {
        e.preventDefault();
        let selectedRows = dataTableGetSelectedRows(dataTable);

        if(selectedRows.length){
            //const params = new URLSearchParams({});
            let currentIds = selectedRows.map(function(item){
                return item.id;
            });

            const form = document.querySelector('#massUpdateForm');
            
            const fields = form.querySelectorAll(
                'input:not(:disabled), select:not(:disabled), textarea:not(:disabled)'
            );

            let payload = {
                ids: currentIds
            };

            fields.forEach(field => {
                payload[field.name] = field.value;
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('seasons.orders.update', [$season, $order]) }}",
                type: 'PATCH',
                data: payload,
            }).done(function(data) {
                //console.log(data);
                $('#athletes-list').html(data);
                /*if (data.type == 'success') {
                    iziToast.success({
                        message: data.message
                    });
                } else {
                    iziToast.error({
                        message: data.message
                    });
                }
                */
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("fail");
                /*iziToast.error({
                    message: jqXHR.responseJSON ? jqXHR.responseJSON.message : textStatus
                });
                */
            }).always(function() {
                console.log("always");
                //is_done = true;
                //$button.attr('disabled', false);
                //Tools.unblockUI();
                //drawDocumentStatus();
                //dataTable.draw(false);
            });

            //window.location.href = "";
        }else{
            alert("Nessuna riga selezionata");
        }
    });

</script>
@endpush
