@extends('backend.layouts.app')

@php
    $entity = __('Aggiungi atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{--<x-backend.section-header>
            {{ $entity }}
        </x-backend.section-header>--}}
    </div>
    {{ html()->form('POST', route("races.subscription.store"))->class('form')->open() }}
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label for="fee_id">{{ __('Gara') }}</label>
                        <select name="fee_id" class="form-control {{ $errors->has('fee_id') ? 'is-invalid' : '' }}">
                            <option value="0">{{ __('Seleziona') }}</option>
                            @foreach ($races as $race)
                                <optgroup label="{{ $race->name }}">
                                    @foreach ($race->fees as $fee)
                                        <option value="{{ $fee->id }}" @if ($fee->id == old('fee_id')) selected @endif>{{ $fee->name }} ({{ $fee->expired_at }} - {{ $fee->amount }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @if ($errors->has('fee_id'))
                            <div class="invalid-feedback">{{ $errors->first('fee_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label for="brand_id">{{ __('Atleti') }}</label>

                        <ul class="list-group">
                            @foreach ($athletes as $athlete)
                                <li class="list-group-item">
                                    <label class="form-check-label">
                                        <input name="athletes[{{ $athlete->id }}]" class="form-check-input {{ $errors->has('athletes.' . $athlete->id) ? 'is-invalid' : '' }}" type="checkbox" value="{{ $athlete->id }}">
                                        {{ $athlete->fullname }}
                                        @if ($errors->has('athletes.' . $athlete->id))
                                            <div style="display:block" class="invalid-feedback">{{ $errors->first('athletes.' . $athlete->id) }}</div>
                                        @endif
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('create', App\Models\Race::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>

@endsection

@push ('after-styles')


@endpush

@push ('after-scripts')

@endpush
