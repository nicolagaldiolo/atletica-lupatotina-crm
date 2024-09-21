@extends('backend.layouts.app')

@php
    $entity = __('Utenti');
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("users.index")}}'> {{ $entity }}</x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ $user->name }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
{{ html()->modelForm($user, 'PATCH', route('users.update', $user))->class('form-horizontal')->open() }}
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col">
                @can('delete', $user)
                    <x-backend.buttons.delete route='{{ route("users.destroy", $user) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
                @endcan
                <div class="float-end">
                    @can('update', $user)
                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        
        <div class="row mb-3">

            <div class="col-12 col-sm-2">
                <div class="form-group">
                    <label for="name">{{ __('Nome') }}</label>
                    <span class="text-danger">*</span>
                </div>
            </div>
            <div class="col-12 col-sm-10">
                <div class="form-group mb-3">
                    <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $user->name) }}">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <?php
            $field_name = 'email';
            $field_lable = __('labels.backend.users.fields.email');
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            <div class="col-12 col-sm-2">
                <div class="form-group">
                    {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                </div>
            </div>
            <div class="col-12 col-sm-10">
                <div class="form-group">
                    {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <?php
            $field_name = 'password';
            $field_lable = __('labels.backend.users.fields.password');
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            <div class="col-12 col-sm-2">
                <div class="form-group">
                    {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                </div>
            </div>
            <div class="col-12 col-sm-10">
                <div class="form-group">
                    <a href="{{ route('users.changePassword', $user->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-key"></i> Change password</a>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3">
            {{ html()->label(__('Abilities'))->class('col-sm-2 form-control-label') }}
            <div class="col">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                @lang('Roles')
                            </div>
                            <div class="card-body">
                                @if ($roles->count())
                                @foreach($roles as $role)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="checkbox">
                                            {{ html()->label(html()->checkbox('roles[]', in_array($role->name, $userRoles), $role->name)->id('role-'.$role->id) . "&nbsp;". ucwords($role->name) . "&nbsp;(".$role->name.")")->for('role-'.$role->id) }}
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
                                <!--card-->
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card card-accent-info">
                            <div class="card-header">
                                @lang('Permissions')
                            </div>
                            <div class="card-body">
                                @if ($permissions->count())
                                @foreach($permissions as $permission)
                                <div class="checkbox">
                                    {{ html()->label(html()->checkbox('permissions[]', in_array($permission->name, $userPermissions), $permission->name)->id('permission-'.$permission->id) . ' ' . $permission->name)->for('permission-'.$permission->id) }}
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{ html()->form()->close() }}

            

@endsection
