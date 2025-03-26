@extends('backend.layouts.app')

@php
    $entity = App\Enums\RaceType::getDescription($raceType);
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
    <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
        @can((($raceType == App\Enums\RaceType::Race) ? 'createRace' : (($raceType == App\Enums\RaceType::Track) ? 'createTrack' : false)), App\Models\Race::class)
            <x-backend.buttons.create route="{{ route('races.create', $raceType) }}" small="true" title="">
                {{ __('Aggiungi') }}
            </x-backend.buttons.create>
        @endcan
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($years->count())
            <div class="row">
                <div class="col-auto">
                    <div class="input-group">
                        <label class="input-group-text fw-bold">{{ __('Seleziona anno') }}</label>
                        <select id="searchByYear" class="form-select">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @if($year == $searchByYear) selected @endif>{{ $year }}</option>    
                            @endforeach
                        </select>
                        <button id="dtSearch" class="btn btn-secondary" type="button">{{ __('Filtra') }}</button>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="row mt-3">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Distanza') }}</th>
                            <th>{{ __('Iscrizioni') }}</th>
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
            url: '{{ route("races.index", $raceType) }}',
            data: function(data){
                data.searchByYear = $('#searchByYear').val();
            }
        },
        order: [[2, 'desc']],
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
                data: 'distance',
                searchable: false,
                orderable: false,
            },
            {
                data: 'athlete_fee_count',
                render(data) {
                    if(data){
                        return '<span class="badge text-bg-secondary">' + data + '</span>';
                    }
                    return null;
                },
                orderable: false,
                searchable: false,
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
            }
        ]
    });

    $('#dtSearch').click(function(){
        dataTable.draw(false);
    })
</script>
@endpush
