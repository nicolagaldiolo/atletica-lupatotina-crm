@extends('backend.layouts.app')

@php
    $entity = __('Certificati');
@endphp

@section('title') {{ $entity }} @endsection

@section('before-breadcrumbs')
    <img class="avatar avatar-lg me-2" src="{{ $athlete->avatar }}">
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canrul="{{ Auth::user()->can('edit', $athlete) }}" route='{{route("athletes.edit", $athlete)}}'>{{ $athlete->fullname }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.athletes.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    @can('create', [App\Models\Certificate::class, $athlete])
        <div class="card-header">
            <x-backend.section-header>
                <x-slot name="toolbar">
                    <x-backend.buttons.create route="{{ route('athletes.certificates.create', $athlete) }}" small="true" title="">
                        {{ __('Aggiungi certificato') }}
                    </x-backend.buttons.create>
                </x-slot>
            </x-backend.section-header>
        </div>
    @endcan
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
        searching: false,
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("athletes.certificates.index", $athlete) }}',
        order: [[2, 'desc']],
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
                data: 'expires_on',
                render(data, type, row, meta) {
                    if(data){
                        return '<span class="badge text-bg-' + row.status.status_class + '">' + row.status.date + ' (' + row.status.date_diff + ')</span>';
                    }

                    return null;
                }
            },
            {
                data: 'document',
                render(data, type, row, meta) {
                    if(row.status.url_download){
                        return '<a class="btn btn-primary btn-sm" href="' + row.status.url_download + '" target="_blank"><i class="fa-solid fa-download"></i> {{ __("Scarica") }}</a>';
                    }else{
                        return '<i class="text-danger fa-solid fa-triangle-exclamation"></i>';
                    }
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });
</script>
@endpush

