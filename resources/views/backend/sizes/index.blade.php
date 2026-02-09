@extends('backend.layouts.app')

@php
    $entity = __('Taglie')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    @can('create', App\Models\Article::class)
                        <x-backend.buttons.create route="{{ route('sizes.create') }}" small="true" title="">
                            {{ __('Aggiungi') }}
                        </x-backend.buttons.create>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-3">
            <div class="col">
                <table id="datatable" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Taglia') }}
                            </th>
                            <th>
                                {{ __('Attivo') }}
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
    let dataTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route("sizes.index") }}'
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
            },
            {
                data: 'is_active',
                render(data, type, row, meta) {
                    if(data){
                        return '<i class="fa-solid fa-check"></i>';
                    }else{
                        return '<i class="fa-solid fa-ban"></i>';
                    }
                },
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
