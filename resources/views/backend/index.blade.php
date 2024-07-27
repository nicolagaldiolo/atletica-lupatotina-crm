@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs />
@endsection

@section('content')
<div class="card mb-4 ">
    <div class="card-body">

        <x-backend.section-header>
            {{ __('Benvenuto') }} {{ Auth::user()->name }}
            @lang("", ['name'=>config('app.name')])

            <x-slot name="subtitle">
                {{ date_today() }}
            </x-slot>
            <x-slot name="toolbar">
                <button class="btn btn-outline-primary mb-1" type="button" data-toggle="tooltip" data-coreui-placement="top" title="Tooltip">
                    <i class="fa-solid fa-bullhorn"></i>
                </button>
            </x-slot>
        </x-backend.section-header>

        <!-- Dashboard Content Area -->

        <!-- / Dashboard Content Area -->

    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="row">
            @if($races_payed->count())
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-success text-white p-3 me-3">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div>
                                <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Gare pagate') }} ({{ $races_payed->count() }})</div>
                                <div class="fs-6 fw-semibold text-success">@money($races_payed->sum('amount'))</div>
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            @if(Auth::user()->athlete)
                                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.races.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai alle gare') }}</span>
                                    <i class="fa-solid fa-circle-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if($races_to_pay->count())
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-danger text-white p-3 me-3">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Gare da pagare') }} ({{ $races_to_pay->count() }})</div>
                                <div class="fs-6 fw-semibold text-danger">@money($races_to_pay->sum('amount'))</div>
                        
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            @if(Auth::user()->athlete)
                                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.races.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai alle gare') }}</span>
                                    <i class="fa-solid fa-circle-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if($certificate)
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-{{ $certificate->status['status_class'] }} text-white p-3 me-3">
                                <i class="fa-solid fa-stethoscope"></i>
                            </div>
                            <div>
                                <div class="text-medium-emphasis text-uppercase fw-semibold small">{{ __('Scadenza certificato') }}</div>
                                <div class="fs-6 fw-semibold text-{{ $certificate->status['status_class'] }}">{{ $certificate->status['date'] }} ({{ $certificate->status['date_diff'] }})</div>
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            @if(Auth::user()->athlete)
                                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="{{ route('athletes.certificates.index', Auth::user()->athlete) }}"><span class="small fw-semibold">{{ __('Vai ai certificati') }}</span>
                                    <i class="fa-solid fa-circle-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-sm-6">
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
                                        {{ __('Certificato') }}
                                    </th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">89.9%</div>
                <div>Widget title</div>
                <div class="progress progress-thin my-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis">Widget helper text</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">12.124</div>
                <div>Widget title</div>
                <div class="progress progress-thin my-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis">Widget helper text</small>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#"><span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">$98.111,00</div>
                <div>Widget title</div>
                <div class="progress progress-thin my-2">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis">Widget helper text</small>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#"><span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">2 TB</div>
                <div>Widget title</div>
                <div class="progress progress-thin my-2">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis">Widget helper text</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->



<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-primary">
            <div class="card-body">
                <div class="fs-4 fw-semibold">89.9%</div>
                <div>Widget title</div>
                <div class="progress progress-white progress-thin my-2">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis-inverse">Widget helper text</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-warning">
            <div class="card-body">
                <div class="fs-4 fw-semibold">12.124</div>
                <div>Widget title</div>
                <div class="progress progress-white progress-thin my-2">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis-inverse">Widget helper text</small>
            </div>
            <div class="card-footer text-white px-3 py-2">
                <a class="btn-block text-white text-medium-emphasis d-flex justify-content-between align-items-center" href="#">
                    <span class="small fw-semibold text-white"> aaa View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-danger">
            <div class="card-body">
                <div class="fs-4 fw-semibold">$98.111,00</div>
                <div>Widget title</div>
                <div class="progress progress-white progress-thin my-2">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis-inverse">Widget helper text</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-info">
            <div class="card-body">
                <div class="fs-4 fw-semibold">2 TB</div>
                <div>Widget title</div>
                <div class="progress progress-white progress-thin my-2">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-medium-emphasis-inverse">Widget helper text</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->


<div class="row">
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary text-white p-3 me-3">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-primary">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#"><span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-info text-white p-3 me-3">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-info">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#"><span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    
</div>
<!-- /.row-->
--}}


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
                visible: false
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
                }
                
            },
            {
                data: null,
                render(data) {
                    return null;
                }
            }
            /*{
                data: 'certificate.expires_on',
                name: 'certificate.expires_on',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-' + data.status.status_class + '">' + data.status.date + ' (' + data.status.date_diff + ')</span>';
                    }

                    return null;
                }
            },
            */
            
        ]
    });
</script>
@endpush
