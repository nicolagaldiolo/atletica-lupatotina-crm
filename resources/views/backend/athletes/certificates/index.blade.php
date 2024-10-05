@extends('backend.layouts.app')

@php
    $entity = __('Atleti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("athletes.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $athlete->fullname }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
                @can('create', [App\Models\Certificate::class, $athlete])
                    <x-buttons.create route="{{ route('athletes.certificates.create', $athlete) }}" small="true" title="">
                        {{ __('Aggiungi certificato') }}
                    </x-buttons.create>
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
                                {{ __('Corrente') }}
                            </th>
                            <th>
                                {{ __('Scadenza') }}
                            </th>
                            <th>
                                {{ __('Documento') }}
                            </th>
                            <th class="text-end">
                                Action
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
        ajax: '{{ route("athletes.certificates.index", $athlete) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'is_current',
                render(data) {
                    if(data){
                        return '<i class="fas fa-check-square"></i>';
                    }

                    return null;
                }
            },
            {
                data: 'status',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-' + data.status_class + '">' + data.date + ' (' + data.date_diff + ')</span>';
                    }

                    return null;
                }
            },
            {
                data: null,
                render(data) {
                    if(data){
                        if(data.status.url_download){
                            return '<a class="btn btn-primary btn-sm" href="' + data.status.url_download + '" target="_blank"><i class="fa-solid fa-download"></i> {{ __("Scarica") }}</a>';
                        }else{
                            return '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                        }
                    }

                    return null;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        ordering: false,
    });
</script>
@endpush

