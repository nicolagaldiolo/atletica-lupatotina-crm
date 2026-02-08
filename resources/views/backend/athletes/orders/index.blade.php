@extends('backend.layouts.app')

@php
    $entity = __('Abbigliamento')
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
    @can('create', [App\Models\Order::class, $athlete])
        <div class="card-header">
            <x-backend.section-header>
                <x-slot name="toolbar">
                    <x-backend.buttons.create route="{{ route('athletes.orders.create', $athlete) }}" small="true" title="">
                        {{ __('Crea ordine') }}
                    </x-backend.buttons.create>
                </x-slot>
            </x-backend.section-header>
        </div>
    @endcan
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                
                @if(!$orders_enabled)
                    <div class="alert alert-danger mb-4 text-center" role="alert">
                        <i class="fas fa-shopping-cart"></i> {{ __('Spiacenti ma in questo momento non è possibile effettuare ordini') }}
                    </div>
                @endif

                <table id="datatable" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('Numero') }}
                            </th>
                            <th>
                                {{ __('Data') }}
                            </th>
                            <th>
                                {{ __('Articoli') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Stato') }}
                            </th>
                            <th>
                                {{ __('Pagamento') }}
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
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("athletes.orders.index", $athlete) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
            },
            {
                data: 'number',
            },
            {
                data: 'created_at',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'quantity',
                render(data, type, row, meta) {
                    if(data){
                        return '<span class="badge text-bg-secondary">' + data + '</span>';
                    }
                    return null;
                }
            },
            {
                data: 'total_amount',
                render(data, type, row, meta) {
                    console.log(data, type, row, meta);
                    return App.money(data);
                },
            },
            {
                data: 'status'
            },
            {
                data: 'payment_status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>
@endpush
