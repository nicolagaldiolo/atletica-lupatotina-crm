@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', $race) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Quote') }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    @include ("backend.races.partials.action_column", ['layout' => 'nav'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
                @can('create', App\Models\Fee::class)
                    <x-backend.buttons.create route="{{ route('races.fees.create', $race) }}" small="true" title="">
                        {{ __('Aggiungi') }}
                    </x-backend.buttons.create>
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
                                {{ __('Nome') }}
                            </th>
                            <th>
                                {{ __('Valida sino al') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
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
        ajax: '{{ route("races.fees.index", $race) }}',
        columns: [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'expired_at',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: 'amount',
                render(data) {
                    return App.money(data);
                },
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
