@extends('backend.layouts.app')

@php
    $entity = __('Utenti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $user->name }}</x-backend-breadcrumb-item>
@endsection

@section('content')
{{ html()->modelForm($user, 'PATCH', route('users.changePasswordUpdate', $user))->class('form-horizontal')->open() }}

<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    @can('viewAny', App\Models\User::class)
                        <x-backend.buttons.return route='{{ route("users.index") }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
                    @endcan
                    @can('update', $user)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="row mt-4 mb-4">
            <div class="col">
                
                <div class="form-group row mb-3">
                    {{ html()->label(__('labels.backend.users.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                    <div class="col-md-10">
                        {{ html()->password('password')
                            ->class('form-control')
                            ->placeholder(__('labels.backend.users.fields.password'))
                            ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-3">
                    {{ html()->label(__('Conferma password'))->class('col-md-2 form-control-label')->for('password_confirmation') }}

                    <div class="col-md-10">
                        {{ html()->password('password_confirmation')
                            ->class('form-control')
                            ->placeholder(__('Conferma password'))
                            ->required() }}
                    </div>
                </div>

            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
        
    </div>
</div>

{{ html()->form()->close() }}

            

@endsection
