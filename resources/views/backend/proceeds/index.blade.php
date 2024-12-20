@extends('backend.layouts.app')

@php
    $entity = __('Atleti')
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
        <div class="row">
            
            <nav class="mb-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="general-tab" data-coreui-toggle="tab" data-coreui-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <i class="far fa-file-alt"></i> Generale
                    </button>
                    <button class="nav-link" id="images-tab" data-coreui-toggle="tab" data-coreui-target="#images" type="button" role="tab" aria-controls="images" aria-selected="false" tabindex="-1">
                        <i class="far fa-images"></i> Immagini
                    </button>
                </div>
            </nav>

            <div class="tab-content mb-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab" tabindex="0">
                    aaa
                </div>
                <div class="tab-pane fade show" id="images" role="tabpanel" aria-labelledby="images-tab" tabindex="1">
                    bbb
                </div>
            </div>
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
