@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can('create', App\Models\Race::class)
            <x-buttons.create route="{{ route('races.create') }}" small="true" title="">
                {{ __('Aggiungi') }}
            </x-buttons.create>
        @endcan
    </div>
@endsection

@section('content')
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
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Iscrizioni') }}</th>
                            <th>{{ __('Chiusura iscrizioni') }}</th>
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
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("races.index") }}',
        order: [[2, 'asc']],
        columns: [{
                data: 'id',
                name: 'id',
                visible:false,
                searchable: false,
                orderable: false,
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'date',
                searchable: false,
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'athlete_fee_count',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-primary">' + data + '</span>';
                    }
                    return null;
                },
                orderable: false,
                searchable: false,
            },
            {
                data: 'subscrible_expiration',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
            }
        ]
    });
</script>
@endpush
