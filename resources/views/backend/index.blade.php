@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ __('Dashboard') }}</x-backend-breadcrumb-item>
@endsection

@section('content')

    <div class="mt-3">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading"><i class="fa-solid fa-coins"></i> Posso pagare una gara tramite bonifico?</h4>
            <p class="mb-0">Effettuare un bonifico bancario con l'importo della gara che si intende saldare specificando i seguenti dati:</p>
            <hr>
            <p class="mb-0">
                <strong>Beneficiario:</strong> ATLETICA LUPATOTINA ASS. SPORTIVA,
                <strong>IBAN:</strong> IT90A0200859770000005580195,
                <strong>Causale:</strong> Nome cognome | Gara/e che si intende saldare
            </p>
        </div>
    </div>

    @if(Auth::user()->athlete)

        <div class="row">
            <div class="col-sm-6 p-3">
                <h5>{{ __('Iscrizioni gare') }}</h5>
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white p-3 me-3">
                                        <i class="fa-solid fa-coins"></i>
                                    </div>
                                    <div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Pagate') }} ({{ $races_payed->count() }})</div>
                                        <div class="fs-6 fw-semibold text-success">@money($races_payed->sum('athletefee.custom_amount'))</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger text-white p-3 me-3">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Da pagare') }} ({{ $races_to_pay->count() }})</div>
                                        <div class="fs-6 fw-semibold text-danger">@money($races_to_pay->sum('athletefee.custom_amount'))</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route("athletes.fees.index", [Auth::user()->athlete, "raceType" => App\Enums\RaceType::Race]) }}">
                            <span class="small fw-semibold">{{ __('Vai alle iscrioni gare') }}</span>
                            <i class="fa-solid fa-circle-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <h5>{{ __('Iscrizioni pista') }}</h5>
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white p-3 me-3">
                                        <i class="fa-solid fa-coins"></i>
                                    </div>
                                    <div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Pagate') }} ({{ $track_payed->count() }})</div>
                                        <div class="fs-6 fw-semibold text-success">@money($track_payed->sum('athletefee.custom_amount'))</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger text-white p-3 me-3">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Da pagare') }} ({{ $track_to_pay->count() }})</div>
                                        <div class="fs-6 fw-semibold text-danger">@money($track_to_pay->sum('athletefee.custom_amount'))</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route("athletes.fees.index", [Auth::user()->athlete, "raceType" => App\Enums\RaceType::Track]) }}">
                            <span class="small fw-semibold">{{ __('Vai alle iscrioni pista') }}</span>
                            <i class="fa-solid fa-circle-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 p-3">

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
        @canany(['registerPaymentRace', 'registerPaymentTrack'], App\Models\AthleteFee::class)
            <div class="col-sm-6 p-3">
                @can('registerPaymentRace', App\Models\AthleteFee::class)
                    <div class="mb-4">
                        <h5>{{ __('Posizioni aperte Gare') }}</h5>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <table id="datatable_athletes_races" class="table table-bordered table-striped table-hover table-responsive-sm">
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
                @can('registerPaymentTrack', App\Models\AthleteFee::class)
                    <div>
                        <h5>{{ __('Posizioni aperte Pista') }}</h5>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <table id="datatable_athletes_tracks" class="table table-bordered table-striped table-hover table-responsive-sm">
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
            </div>
        @endcanany
        
        @can('viewAny', [App\Models\Certificate::class, null])
            <div class="col-sm-6 p-3">
                <h5>{{ __('Certificati in scadenza') }} (@date(Carbon\Carbon::now()->addMonth()->endOfMonth()))</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table id="datatable_certificates" class="table table-bordered table-striped table-hover table-responsive-sm">
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
                                                {{ __('Documento') }}
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
@endpush

@push ('after-scripts')

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
            order: [[ 2, "asc" ]],
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
                                '<img class="avatar avatar-sm me-2" src="' + row.avatar + '">',
                                row.fullname
                            ];
                            return html.join("");
                        }

                        return null;
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
                    data: 'certificate',
                    render(data, type, row, meta) {
                        if(data.status.url_download){
                            return '<a class="btn btn-secondary btn-sm" href="' + data.status.url_download + '" target="_blank"><i class="fa-solid fa-download"></i> {{ __("Scarica") }}</a>';
                        }else{
                            return '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                        }
                    },
                    searchable: false
                },
            ]
        });
    @endcan

    @can('registerPaymentRace', App\Models\AthleteFee::class)
        $('#datatable_athletes_races').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: "{{ route('dashboard.fees', App\Enums\RaceType::Race) }}",
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
                    name: 'name',
                    render(data, type, row, meta) {
                        if(data){
                            var html = [
                                '<img class="avatar avatar-sm me-2" src="' + row.avatar + '">',
                                row.fullname
                            ];
                            return html.join("");
                        }

                        return null;
                    },
                },
                {
                    data: 'fees_to_pay',
                    render(data) {
                        if(data && data.length){
                            let html = data.reduce((i, item) => {
                                let data = [
                                    item.race.name + " (" + item.name + " - " + App.money(item.amount) + ")",
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

                                i.push("<small>" + data.join(" ") + "</small>");
                                return i;
                            }, []);
                            return (html.length) ? ('<span>' + html.join("<hr class='p-1 m-0'>") + '</span>') : null;
                        }
                        return null;
                    },
                    searchable: false,
                    orderable: false,
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
            ],
            pageLength: 10,
        });
    @endcan

    @can('registerPaymentTrack', App\Models\AthleteFee::class)
        $('#datatable_athletes_tracks').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: "{{ route('dashboard.fees', App\Enums\RaceType::Track) }}",
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
                    name: 'name',
                    render(data, type, row, meta) {
                        if(data){
                            var html = [
                                '<img class="avatar avatar-sm me-2" src="' + row.avatar + '">',
                                row.fullname
                            ];
                            return html.join("");
                        }

                        return null;
                    },
                },
                {
                    data: 'fees_to_pay',
                    render(data) {
                        if(data && data.length){
                            let html = data.reduce((i, item) => {
                                let data = [
                                    item.race.name + " (" + item.name + " - " + App.money(item.amount) + ")",
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

                                i.push("<small>" + data.join(" ") + "</small>");
                                return i;
                            }, []);
                            return (html.length) ? ('<span>' + html.join("<hr class='p-1 m-0'>") + '</span>') : null;
                        }
                        return null;
                    },
                    searchable: false,
                    orderable: false,
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
            ],
            pageLength: 10,
        });
    @endcan
</script>
@endpush
