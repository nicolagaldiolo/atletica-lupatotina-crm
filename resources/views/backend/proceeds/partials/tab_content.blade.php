<div class="tab-pane fade @if($loop->first)active show @endif" id="account-{{$account->id}}" role="tabpanel" aria-labelledby="account-{{$account->id}}-tab" tabindex="{{ $loop->index }}">
    <div class="row">
        <div class="col-7">
            <div class="p-3">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-cash-register fa-lg"></i> <strong>{{ __('Incassato da scaricare') }}</strong></h4>
                </div>

                @can('deductPayment', App\Models\AthleteFee::class)
                    <button style="display: none;" id="massUpdate-{{ $account->id }}" class="btn btn-primary" data-placement="top" title="{{ __('Scarica incasso') }}">
                        <i class="fas fa-cash-register fa-lg"></i> {{ _('Scarica incasso') }}
                    </button>
                @endcan
                
                <table id="datatable-{{ $account->id }}" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="selectAll-{{ $account->id }}" name="selectAll">
                            </th>
                            <th>
                                {{ __('Socio') }}
                            </th>
                            <th>
                                {{ __('Gara') }}
                            </th>
                            <th>
                                {{ __('Pagamento') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>    
        <div class="col-5">
            <div class="p-3">
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-chart-line fa-lg"></i> <strong>{{ __('Incassato scaricato') }}</strong></h4>
                </div>
                <table id="datatable-deducted-{{ $account->id }}" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                {{ __('Data') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>    
    </div>
</div>


@push ('after-scripts')

<script type="module">

    var canDeductPayment = @can('deductPayment', App\Models\AthleteFee::class) true @else false @endcan;

    function dataTableGetSelectedRows(dt){
        return dt.rows({ selected: true }).data().toArray();
    }

    let dataTable_deducted_{{ $account->id }} = $('#datatable-deducted-{{ $account->id }}').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route("proceeds.deducted", $account->id) }}'
        },
        order: [[ 0, "desc" ]],
        columns: [
            {
                data: 'deduct_at',
                searchable: false,
                render(data, type, row, meta) {
                    return App.date(data, false, false);
                },
            },
            {
                data: 'amount',
                searchable: false,
                orderable: false,
                render(data, type, row, meta) {
                    return App.money(data);
                },
            },
        ],
        // GLi altri elmenti di layout vengono ereditati dal default
        layout: {            
            topEnd: 'buttons'
        },
    });

    let dataTable_{{ $account->id }} = $('#datatable-{{ $account->id }}').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route("proceeds.show", $account->id) }}'
        },
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        order: [[ 3, "desc" ]],
        columns: [
            {
                data: null,
                defaultContent: '',
                orderable: false,
                className: 'select-checkbox dt-head-center',
                visible: canDeductPayment
            },
            {
                data: 'name'
            },
            {
                data: 'fee.race.name',
            },
            {
                data: 'payed_at',
                render(data) {
                    return data ? App.date(data) : null;
                },
            },
            {
                data: 'custom_amount',
                render(data, type, row, meta) {
                    return App.money(data);
                }
            },
        ]
    });
    
    if(canDeductPayment){
        let massUpdateBtn_{{ $account->id }} = $('#massUpdate-{{ $account->id }}');
        let selectAllBtn_{{ $account->id }} = $(".selectAll-{{ $account->id }}");

        dataTable_{{ $account->id }}.on('select deselect', function ( e, dt, type, indexes ) {
            if ( type === 'row' ) {
                if(dataTableGetSelectedRows(dt).length){
                    massUpdateBtn_{{ $account->id }}.show();
                }else{
                    massUpdateBtn_{{ $account->id }}.hide();
                }
            }
        });

        selectAllBtn_{{ $account->id }}.on( "click", function(e) {
            if ($(this).is( ":checked" )) {
                dataTable_{{ $account->id }}.rows().select();
            } else {
                dataTable_{{ $account->id }}.rows().deselect();
            }
        });

        massUpdateBtn_{{ $account->id }}.click( function (e) {
            e.preventDefault();
            let _self = $(this);
            
            if(confirm("Sei sicuro?")){
                let selectedRows = dataTableGetSelectedRows(dataTable_{{ $account->id }});
                if(selectedRows.length){
                    
                    let ids = selectedRows.map(function(item){
                        return item.id;
                    });

                    $.ajax({
                        type: 'PATCH',
                        url: '{{ route("proceeds.update", $account->id) }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        }

                    }).done(function(data) {
                        dataTable_{{ $account->id }}.draw(false);
                        dataTable_deducted_{{ $account->id }}.draw(false);
                        selectAllBtn_{{ $account->id }}.prop('checked', false);
                        _self.hide();
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("fail");
                    }).always(function() {
                        console.log("always");
                    });


                }else{
                    alert("Nessuna riga selezionata");
                }
            }else{
                dataTable_{{ $account->id }}.draw(false);
                dataTable_deducted_{{ $account->id }}.draw(false);
                selectAllBtn_{{ $account->id }}.prop('checked', false);
                _self.hide();
            }
        });
    }
</script>

@endpush