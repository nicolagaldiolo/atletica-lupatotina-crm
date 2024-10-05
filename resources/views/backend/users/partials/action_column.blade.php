<div class="text-end">
    @can('update', $user)
        <x-backend.buttons.edit route='{{ route("users.edit", $user) }}' small="true" />
        <x-backend.buttons.edit icon="fas fa-key" route="{{ route('users.changePassword', $user) }}" small="true" />
    @endcan
    @can('block', $user)
        @if ($user->status != 2)
            <x-backend.buttons.update icon="fas fa-ban" route="{{ route('users.block', $user) }}" small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
        @endif
        @if ($user->status == 2)
            <x-backend.buttons.update icon="fas fa-check" route="{{ route('users.unblock', $user) }}" small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
        @endif
    @endcan
    @can('delete', $user)
        <x-backend.buttons.delete route="{{route('users.destroy', $user)}}" small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
