@extends('backend.layouts.app')

@php
    $entity = __('Utenti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("races.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __('Nuovo utente') }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    {{ html()->form('POST', route("users.store"))->class('form')->open() }}
        <div class="card-header">

            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <div class="form-group">
                            @can('viewAny', App\Models\Users::class)
                                <x-backend.buttons.return route='{{ route("users.index") }}' small="true">{{ __('Annulla') }}</x-backend.buttons.return>
                            @endcan
                            @can('create', App\Models\Users::class)
                                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <label for="name">{{ __('Nome') }}</label>
                                <span class="text-danger">*</span>
                                <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $user->name) }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <label for="email">{{ __('Email') }}</label>
                                <span class="text-danger">*</span>
                                <input name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" value="{{ old('email', $user->email) }}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <label for="password">{{ __('Password') }}</label>
                                <span class="text-danger">*</span>
                                <input name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" value="{{ old('password', $user->password) }}">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation">{{ __('Conferma password') }}</label>
                                <span class="text-danger">*</span>
                                <input name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" type="password" value="{{ old('password_confirmation', $user->password_confirmation) }}">
                                @if ($errors->has('password_confirmation'))
                                    <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <div class="card card-accent-info">
                                    <div class="card-header">
                                        @lang('Ruoli')
                                    </div>
                                    <div class="card-body">
                                        @if ($roles->count())
                                            @foreach($roles as $role)
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <div class="checkbox">
                                                            {{ html()->label(html()->checkbox('roles[]', old('roles') && in_array($role->name, old('roles')) ? true : false, $role->name)->id('role-'.$role->id) . "&nbsp;" . ucwords($role->name). "&nbsp;(".$role->name.")")->for('role-'.$role->id) }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($role->id != 1)
                                                            @if ($role->permissions->count())
                                                                @foreach ($role->permissions as $permission)
                                                                    <i class="far fa-check-circle mr-1"></i>&nbsp;{{ $permission->name }}&nbsp;
                                                                @endforeach
                                                            @else
                                                                @lang('None')
                                                            @endif
                                                        @else
                                                            @lang('All Permissions')
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <div class="card card-accent-primary">
                                    <div class="card-header">
                                        @lang('Permessi')
                                    </div>
                                    <div class="card-body">
                                        @if ($permissions->count())
                                            @foreach($permissions as $permission)
                                                <div class="checkbox">
                                                    {{ html()->label(html()->checkbox('permissions[]', old('permissions') && in_array($permission->name, old('permissions')) ? true : false, $permission->name)->id('permission-'.$permission->id) . ' ' . $permission->name)->for('permission-'.$permission->id) }}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    @push('after-styles')
                    @endpush
                    
                    @push ('after-scripts')
                    @endpush
                    
                </div>
            </div>
        </div>

    {{ html()->form()->close() }}
</div>

@endsection
