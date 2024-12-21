@extends('backend.layouts.app')

@php
    $entity = __('Incassi')
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
        <div class="rowa">
            @if (count($accounts))

                <nav class="mb-3">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @foreach ($accounts as $account)
                            <button class="nav-link @if($loop->first) active @endif" id="account-{{$account->id}}-tab" data-coreui-toggle="tab" data-coreui-target="#account-{{$account->id}}" type="button" role="tab" aria-controls="account-{{$account->id}}" aria-selected="true">
                                {{$account->name}}
                            </button>
                        @endforeach
                    </div>
                </nav>
                
                <div class="tab-content" id="nav-tabContent">
                    @foreach ($accounts as $account)
                        @include('backend.proceeds.partials.tab_content')
                    @endforeach
                </div>

            @endif
        </div>

    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

<script type="module">
</script>
@endpush
