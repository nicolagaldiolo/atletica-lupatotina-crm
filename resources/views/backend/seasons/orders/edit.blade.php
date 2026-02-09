@extends('backend.layouts.app')

@php
    $entity = __('Stagioni');
@endphp

@section('title')
    {{ $entity }}
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="" route="">{{ $season->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $order->number }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        @can('ViewAny', [App\Models\Order::class, new App\Models\Athlete()])
                            <x-backend.buttons.return route="{{ route('seasons.orders.index', $season) }}" small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">

                    <div id="massUpdateForm">
                        {{ html()->modelForm($order, 'PATCH', 'seasons.orders.update', [$season, $order])->class('form')->open() }}

                        <ul class="list-group w-100">
                            <li class="list-group-item list-group-item-secondary">

                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" name="massive_enable_status"
                                        value="1" role="switch" id="switchCheckDefault">
                                    <label class="form-check-label"
                                        for="switchCheckDefault">{{ __('Gestisci Consegna') }}</label>
                                </div>
                            </li>
                            <li class="list-group-item delivery-item" style="display:none;">
                                <select class="form-select" name="status">
                                    @foreach (App\Enums\OrderRowStatus::asSelectArray() as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="list-group-item list-group-item-secondary">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" name="massive_enable_payment"
                                        value="1" role="switch" id="switchCheckDefault1">
                                    <label class="form-check-label"
                                        for="switchCheckDefault1">{{ __('Gestisci Pagamento') }}</label>
                                </div>
                            </li>

                            <li class="list-group-item payment-item" style="display:none;">
                                <input class="form-check-input me-1" type="radio" name="payed" value="0"
                                    id="radio_no_payed" checked>
                                <label class="form-check-label" for="radio_no_payed">{{ __('Non pagato') }}</label>
                            </li>

                            <li class="list-group-item payment-item" style="display:none;">
                                <input class="form-check-input me-1" type="radio" name="payed" value="1"
                                    id="radio_payed">
                                <label class="form-check-label" for="radio_payed">{{ __('Pagato') }}</label>

                                <ul class="list-group mt-2 payed-type-item" style="display:none;">
                                    <li class="list-group-item">
                                        <input class="form-check-input me-1" type="radio" name="payment"
                                            value="bank_transfer" id="radio_payment_bank" checked>
                                        <label class="form-check-label" for="radio_payment_bank">{{ __('Pagato con bonifico') }}</label>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="d-flex">
                                            <div class="d-flex align-items-center me-2">
                                                <input class="form-check-input me-1" type="radio" name="payment"
                                                    value="cash" id="radio_payment_cash">
                                                <label class="form-check-label text-nowrap flex-shrink-0"
                                                    for="radio_payment_cash">{{ __('Pagato in contanti') }}</label>
                                            </div>
                                            <select class="form-select" name="cashed_by" disabled>
                                                @foreach ($accountants as $accountant)
                                                    <option @if (Auth::id() == $accountant->id) selected @endif
                                                        value="{{ $accountant->id }}">{{ $accountant->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <li class="list-group-item">
                                <div class="d-grid">
                                    <button id="massUpdate" class="btn btn-primary" disabled>
                                        <i class="fas fa-edit pr-4"></i> {{ __('Aggiorna selezionati') }}
                                    </button>
                                </div>
                            </li>
                        </ul>
                        {{ html()->form()->close() }}
                    </div>

                    <div class="mt-4">
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
    </div>
@endsection

@push('after-styles')
@endpush

@push('after-scripts')
    <script type="module">
        function dataTableGetSelectedRows(dt) {
            return dt.rows({
                selected: true
            }).data().toArray();
        }

        var massUpdateForm = $('#massUpdateForm');
        var massUpdateBtn = $('#massUpdate');

        var dataTable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            responsive: true,
            ajax: '{{ route('seasons.orders.edit', [$season, $order]) }}',
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [{
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

                        if (row.article && row.article.image_default && row.article.image_default.public_url) {
                            html.push("<img width='50' src='" + row.article.image_default.public_url + "' />");
                        }

                        html.push(data);

                        return html.join(" ");
                    }
                },
                {
                    data: 'size.name',
                },
                {
                    data: 'quantity',
                },
                {
                    data: 'amount',
                    render(data, type, row, meta) {
                        return data ? App.money(data) : 0;
                    }
                },
                {
                    data: 'total_amount',
                    render(data, type, row, meta) {
                        return data ? App.money(data) : 0;
                    }
                },
                {
                    data: 'status',
                },
                {
                    data: 'is_payed',
                    orderable: false,
                    searchable: false,
                    render(data, type, row, meta) {
                        if (row.transaction && row.transaction.payed_at) {
                            return [
                                App.date(row.transaction.payed_at),
                                (row.transaction && row.transaction.bank_transfer) ?
                                '<span class="badge text-bg-secondary"><i class="fa-solid fa-landmark"></i> Bonifico</span>' :
                                '<span class="badge text-bg-success"><i class="fa-solid fa-coins"></i> Contanti</span>'
                            ].join("<br>");
                        } else {
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

        dataTable.on('select deselect', function(e, dt, type, indexes) {
            if (type === 'row') {
                if (dataTableGetSelectedRows(dataTable).length) {
                    //massUpdateForm.show();
                } else {
                    //massUpdateForm.hide();
                }
            }
        });

        $(".selectAll").on("click", function(e) {
            if ($(this).is(":checked")) {
                dataTable.rows().select();
            } else {
                dataTable.rows().deselect();
            }
        });

        let massUpdateBtnHandler = function() {
            console.log("Eccomi");
            massUpdateBtn.prop('disabled', !($('[name="massive_enable_status"]').is(':checked') || $('[name="massive_enable_payment"]').is(':checked')));
        };

        $('[name="massive_enable_status"]').on('change', function() {
            const self_checked = $(this).is(':checked');

            if (self_checked) {
                $('[name="status"]').closest('.list-group-item').show();
            } else {
                $('[name="status"]').closest('.list-group-item').hide();
            }

            massUpdateBtnHandler();
        });

        $('[name="massive_enable_payment"]').on('change', function() {
            const self_checked = $(this).is(':checked');

            if (self_checked) {
                $('.payment-item').show();
            } else {
                $('.payment-item').hide();
            }

            //payed.prop('disabled', !self_checked);

            massUpdateBtnHandler();
        });

        $('[name="payment"]').on('change', function() {
            $('[name="cashed_by"]').prop('disabled', $(this).val() == 'bank_transfer');
        })

        $('[name="payed"]').on('change', function() {

            if (parseInt($(this).val())) {
                $('.payed-type-item').show();
            } else {
                $('.payed-type-item').hide();
            }

        })

        massUpdateBtn.click(function(e) {
            e.preventDefault();
            let selectedRows = dataTableGetSelectedRows(dataTable);

            if (selectedRows.length) {
                let currentIds = selectedRows.map(function(item) {
                    return item.id;
                });

                let payload = {
                    ids: currentIds,
                    massive_enable_status: (($('[name="massive_enable_status"]').is(':checked')) ? 1 : 0),
                    status: ($('[name="status"]').val()),
                    massive_enable_payment: (($('[name="massive_enable_payment"]').is(':checked')) ? 1 : 0),
                    payed: parseInt($('[name="payed"]:checked').val()),
                    bank_transfer: ($('[name="payment"]:checked').val() == 'bank_transfer'),
                    cashed_by: ($('[name="cashed_by"]').val())
                };

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
                }).always(function() {
                    window.location.reload();
                });
            } else {
                alert("Nessuna riga selezionata");
            }
        });
    </script>
@endpush
