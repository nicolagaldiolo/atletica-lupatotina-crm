@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs />
@endsection

@section('content')
    @if(Auth::user()->athlete)
        <div class="row">
            <div class="col-sm-6">
                <h5>{{ __('Le mie gare') }}</h5>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="bg-success text-white p-3 me-3">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <div>
                                    <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Gare pagate') }} ({{ $races_payed->count() }})</div>
                                    <div class="fs-6 fw-semibold text-success">@money($races_payed->sum('athletefee.custom_amount'))</div>
                                </div>
                            </div>
                            <div class="card-footer px-3 py-2">
                                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.races.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai alle gare') }}</span>
                                    <i class="fa-solid fa-circle-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="bg-danger text-white p-3 me-3">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div>
                                    <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Gare da pagare') }} ({{ $races_to_pay->count() }})</div>
                                    <div class="fs-6 fw-semibold text-danger">@money($races_to_pay->sum('athletefee.custom_amount'))</div>
                            
                                </div>
                            </div>
                            <div class="card-footer px-3 py-2">
                                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.races.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai alle gare') }}</span>
                                    <i class="fa-solid fa-circle-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">

                <h5>{{ __('Il mio certificato') }}</h5>
                @php
                    $certificate_status_class = $certificate->status['status_class'] ?? 'secondary';
                    $certificate_info = $certificate ? ($certificate->status['date'] . '(' . $certificate->status['date_diff'] . ')') : __('Nessun cartificato disponibile')
                @endphp
                <div class="card mb-4">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-{{ $certificate_status_class }} text-white p-3 me-3">
                            <i class="fa-solid fa-stethoscope"></i>
                        </div>
                        <div>
                            <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Scadenza certificato') }}</div>
                            <div class="fs-6 fw-semibold text-{{ $certificate_status_class }}">{{ $certificate_info }}</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.certificates.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai ai certificati') }}</span>
                            <i class="fa-solid fa-circle-chevron-right"></i>
                        </a>
                    </div>
                </div> 
            </div>
        </div>
    @endif

    <div class="row">
        @can('registerPayment', App\Models\Race::class)
            <div class="col-sm-6">
                <h5>{{ __('Posizioni aperte') }}</h5>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table id="datatable_athletes" class="table table-bordered table-hover table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                {{ __('Nome') }}
                                            </th>
                                            <th>
                                                {{ __('Gare') }}
                                            </th>
                                            <th>
                                                {{ __('Totale') }}
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endcan
        @can('viewAny', [App\Models\Certificate::class, null])
            <div class="col-sm-6">
                <h5>{{ __('Certificati in scadenza') }} (@date(Carbon\Carbon::now()->addMonth()->endOfMonth()))</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table id="datatable_certificates" class="table table-bordered table-hover table-responsive-sm">
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
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
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
    @can('viewAny', [App\Models\Certificate::class, null])
        $('#datatable_certificates').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: "{{ route('dashboard.certificates') }}",
                type: "GET",
                "datatype": 'json'
            },
            //order: [[ 2, "asc" ]],
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false
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
                
            ]
        });
    @endcan

    @can('registerPayment', App\Models\Race::class)
        $('#datatable_athletes').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: "{{ route('dashboard.fees') }}",
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
                    data: 'fees_to_pay',
                    render(data) {
                        if(data && data.length){
                            let html = data.reduce((i, item) => {
                                let data = [
                                    item.race.name + " (" + item.name + ")",
                                    "<strong>" + App.money(item.athletefee.custom_amount) + "</strong>"
                                ];
                                
                                if(item.athletefee.voucher){
                                    let amount = App.money(item.athletefee.voucher.amount_calculated);
                                    if(item.athletefee.voucher.type == "{{ App\Enums\VoucherType::Credit }}"){
                                        data.push('<span class="badge text-bg-success">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Credit) }} (' + amount + ')</span>');
                                    }else{
                                        data.push('<span class="badge text-bg-danger">{{ App\Enums\VoucherType::getDescription(App\Enums\VoucherType::Penalty) }} (' + amount + ')</span>');
                                    }
                                }

                                i.push("<li>" + data.join(" ") + "</li>");
                                return i;
                            }, []);
                            return (html.length) ? ('<ul>' + html.join("") + '</ul>') : null;
                        }
                        return null;
                    },
                    searchable: false,
                },
                {
                    data: 'fees_to_pay',
                    render(data) {
                        if(data && data.length){
                            let amount = data.reduce((i, item) => i+item.athletefee.custom_amount, 0);
                            return App.money(amount);
                        }
                        return null;
                    },
                    searchable: false,
                    orderable: false,
                },
            ]
        });
    @endcan
</script>
@endpush
