@extends('backend.layouts.app')

@php
    $entity = __('Gare')
@endphp

@section('title') {{ $entity }} @endsection
{{-- 
@section('breadcrumbs')
    <x-backend-breadcrumb-item canurl="{{ Auth::user()->can('update', $race) }}" route="{{ route('races.edit', [$race->type, $race]) }}">{{ $race->name }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Quote') }}</x-backend-breadcrumb-item>
@endsection
--}}

@section('secondary-nav')
    {{--@include ("backend.races.partials.action_column", ['layout' => 'nav'])--}}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <x-backend.section-header>
            <x-slot name="toolbar">
                {{--@can((($race->type == App\Enums\RaceType::Race) ? 'createRace' : (($race->type == App\Enums\RaceType::Track) ? 'createTrack' : false)), App\Models\Fee::class)--}}
                    <x-backend.buttons.create route="{{ route('seasons.orders.create', $season) }}" small="true" title="">
                        {{ __('Aggiungi') }}
                    </x-backend.buttons.create>
                {{--  @endcan --}}
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
                                {{ __('Ordinante') }}
                            </th>
                            <th>
                                {{ __('Data ordine') }}
                            </th>
                            <th>
                                {{ __('Importo') }}
                            </th>
                            <th>
                                {{ __('Stato') }}
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
        ajax: '{{ route("seasons.orders.index", $season) }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false,
            },
            {
                data: null,
                //name: 'name'
                render(data) {
                    return 'Nicola';
                },
            },
            {
                data: 'created_at',
                render(data) {
                    return App.date(data);
                },
            },
            {
                data: null,
                render(data) {
                    return 100;
                    //return App.money(data);
                },
            },
            {
                data: null,
                render(data) {
                    return 'Consegnato';
                    //return App.money(data);
                },
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
