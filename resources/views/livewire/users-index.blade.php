<div>
    <div class="row mt-4">
        <div class="col">
            <input type="text" class="form-control my-2" placeholder=" Search" wire:model.live="searchTerm" />

            <div class="table-responsive">
                <table class="table table-hover table-responsive-sm" wire:loading.class="table-secondary">
                    <thead>
                        <tr>
                            <th>{{ __('labels.backend.users.fields.name') }}</th>
                            <th>{{ __('labels.backend.users.fields.email') }}</th>
                            <th>{{ __('labels.backend.users.fields.status') }}</th>
                            <th>{{ __('labels.backend.users.fields.roles') }}</th>
                            <th class="text-end">{{ __('labels.backend.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>
                                <strong>
                                    <a href="{{route('users.show', $user->id)}}">
                                        {{ $user->name }}
                                    </a>
                                </strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {!! $user->status_label !!}
                                {!! $user->confirmed_label !!}
                            </td>
                            <td>
                                @if($user->getRoleNames()->count() > 0)
                                <ul class="fa-ul">
                                    @foreach ($user->getRoleNames() as $role)
                                    <li><span class="fa-li"><i class="fas fa-check-square"></i></span> {{ ucwords($role) }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </td>
                            <td class="text-end">
                                @can('edit_users')
                                <a href="{{route('users.edit', $user)}}" class="btn btn-primary btn-sm mt-1" data-toggle="tooltip" title="{{__('labels.backend.edit')}}"><i class="fas fa-wrench"></i></a>
                                <a href="{{route('users.changePassword', $user)}}" class="btn btn-info btn-sm mt-1" data-toggle="tooltip" title="{{__('labels.backend.changePassword')}}"><i class="fas fa-key"></i></a>
                                @if ($user->status != 2)
                                <a href="{{route('users.block', $user)}}" class="btn btn-danger btn-sm mt-1" data-method="PATCH" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.block')}}" data-confirm="Are you sure?"><i class="fas fa-ban"></i></a>
                                @endif
                                @if ($user->status == 2)
                                <a href="{{route('users.unblock', $user)}}" class="btn btn-info btn-sm mt-1" data-method="PATCH" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.unblock')}}" data-confirm="Are you sure?"><i class="fas fa-check"></i></a>
                                @endif
                                <a href="{{route('users.destroy', $user)}}" class="btn btn-danger btn-sm mt-1" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.delete')}}" data-confirm="Are you sure?"><i class="fas fa-trash-alt"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="float-left">
                {!! $users->total() !!} {{ __('labels.backend.total') }}
            </div>
        </div>
        <div class="col-5">
            <div class="float-end">
                {!! $users->links() !!}
            </div>
        </div>
    </div>
</div>