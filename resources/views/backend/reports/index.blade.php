@extends('backend.layouts.app')

@php
    $entity = __('Reports')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('secondary-nav')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{ __('Estrazioni') }}
    </div>
    <div class="card-body">
        @if($years->count())
            {{ html()->form('POST', route("reports.download"))->class('form')->open() }}
                <div class="row">
                    <div class="col-xxl-3 mb-3">
                        <div class="input-group">
                            <label class="input-group-text">{{ __('Anno') }}</label>
                            <select name="year" id="searchByYear" class="form-select">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" @if($year == $searchByYear) selected @endif>{{ $year }}</option>    
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl mb-3">
                        <div class="input-group">
                            <label class="input-group-text" for="raceLists">{{ __('Gara') }}</label>
                            <select name="race_id" class="form-select" id="raceLists"></select>
                        </div>
                    </div>
                    <div class="col-xxl mb-3">
                        <div class="input-group">
                            <label class="input-group-text" for="athleteLists">{{ __('Atleta') }}</label>
                            <select name="athlete_id" class="form-select" id="athleteLists">
                                <option value="">{{ __('Seleziona') }}</option>
                                @foreach ($athletes as $athlete)
                                    <option value="{{ $athlete->id }}">{{ $athlete->fullname }}</option>    
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col">
                        <button name="report_type" value="{{ App\Enums\ReportType::SituazioneSoci }}" class="btn btn-primary w-100" type="submit">
                            <i class="nav-icon fas fa-coins"></i>&nbsp;{{ __('Situazione soci') }}
                        </button>
                    </div>
                    <div class="col">
                        <button name="report_type" value="{{ App\Enums\ReportType::PartecipazioneGare }}" class="btn btn-primary w-100" type="submit">
                            <i class="nav-icon fas fa-solid fa-flag-checkered"></i>&nbsp;{{ __('Partecipazione gare') }}
                        </button>
                    </div>
                </div>  
            {{ html()->form()->close() }}
        @endif
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#searchByYear').on('change', function(event) {
            
            $race_lists_obj = $('#raceLists');
            $athlete_lists_obj = $('#athleteLists');

            $race_lists_obj.prop('selectedIndex',0);
            $athlete_lists_obj.prop('selectedIndex',0);

            var year = $(event.target.options[event.target.selectedIndex]).val();

            let endpoint_url = '{{ url("") }}/reports/' + year + '/races';
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: endpoint_url,
            }).done(function(data) {
                $race_lists_obj.html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("fail");
            }).always(function() {
                console.log("always");
            });
        });
        $('#searchByYear').trigger('change');

    });
</script>
@endpush
