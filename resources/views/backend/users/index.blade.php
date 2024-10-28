@extends('backend.layouts.app')

@php
    $entity = __('Utenti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can('create', App\Models\User::class)
            <x-backend.buttons.create route="{{ route('users.create') }}" small="true" title="">
                {{ __('Aggiungi') }}
            </x-backend.buttons.create>
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
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Ruoli') }}</th>
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
        ajax: '{{ route("users.index") }}',
        order: [[1, 'asc']],
        columns: [{
                data: 'id',
                name: 'id',
                visible:false,
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'email',
                name: 'email',
                searchable: false,
            },
            {
                data: 'status',
                name: 'status',
                searchable: false,
                render(data) {
                    var result = null;
                    console.log("data", data);
                    switch (data) {
                        case 1:
                            result = '<span class="badge bg-success">Attivo</span>';
                            break;

                        case 2:
                            result = '<span class="badge bg-warning text-dark">Bloccato</span>';
                            break;

                        default:
                            result = '<span class="badge bg-primary">Status:' + data + '</span>';
                            break;
                    }
                    return result;
                    
                },
            },
            {
                data: 'roles',
                name: 'roles',
                searchable: false,
                render(data) {
                    if(data.length){
                        var result = data.reduce(function(arr,role){
                            arr.push('<li><span class="fa-li"><i class="fas fa-check-square"></i></span> ' + role.name + '</li>');
                            return arr;
                        }, []);
                        return "<ul class='fa-ul'>" + result.join("") + "</ul>";
                    }else{
                        return null;
                    }
                },
                orderable: false,
                searchable: false
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
