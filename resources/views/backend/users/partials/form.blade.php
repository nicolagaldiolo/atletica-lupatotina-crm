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
                                        <div class="form-check">
                                            <input class="form-check-input" id="role-1" name="roles[]" type="checkbox" value="super admin" checked="">
                                            <label class="form-check-label" for="role-1">Super Admin (super admin)</label>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        {{ html()->label(html()->checkbox('roles[]', old('roles') && in_array($role->name, old('roles')) ? true : false, $role->name)->id('role-'.$role->id) . "&nbsp;" . ucwords($role->name). "&nbsp;(".$role->name.")")->for('role-'.$role->id) }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if ($role->permissions->count())
                                        @foreach ($role->permissions as $permission)
                                            <i class="far fa-check-circle mr-1"></i>&nbsp;{{ $permission->name }}&nbsp;
                                        @endforeach
                                    @else
                                        @lang('None')
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
