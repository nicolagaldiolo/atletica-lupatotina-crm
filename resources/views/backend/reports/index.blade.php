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
    <div class="card-body">

        {{--@if($years->count())
            <div class="row">
                <div class="col-auto">
                    <div class="input-group">
                        <label class="input-group-text fw-bold">{{ __('Seleziona anno') }}</label>
                        <select id="searchByYear" class="form-select">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @if($year == $searchByYear) selected @endif>{{ $year }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        <hr>--}}

        <a class="btn btn-primary" href="{{ route('reports.download') }}">
            <i class="nav-icon fas fa-download"></i>&nbsp;{{ __('Situazione soci') }}
        </a>

        {{--
        <hr>

        <div class="input-group">
            <label class="input-group-text" for="inputGroupFile01">Filtra per gara</label>
            <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                <option selected>Choose...</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <label class="input-group-text" for="inputGroupFile01">Filtra per atleta</label>
            <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                <option selected>Choose...</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}">{{ $athlete->fullname }}</option>    
                @endforeach
            </select>
            <button class="btn btn-primary" type="button">Button</button>
        </div>
        --}}
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#searchByYear').on('change', function(event) {
            
            console.log($(event.target.options[event.target.selectedIndex]).val());

            //var race_id = event.target.options[event.target.selectedIndex].dataset.race;
            //var fee_id = event.target.options[event.target.selectedIndex].dataset.fee;
            /*
            let endpoint_url = '{{ url("") }}/races/' + race_id + '/fees/' + fee_id + '/athletesSubscribeable';
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: endpoint_url,
            }).done(function(data) {
                $('#athletes-list').html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("fail");
            }).always(function() {
                console.log("always");
            });
            */
        });
        $('#searchByYear').trigger('change');

    });
</script>
@endpush
