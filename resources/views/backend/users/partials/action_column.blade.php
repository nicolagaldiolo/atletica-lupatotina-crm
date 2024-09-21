<div class="text-end">
    @can('update', $user)
        <x-backend.buttons.edit route='{{ route("users.edit", $user) }}' small="true" />
    @endcan

    <x-backend.buttons.edit icon="fas fa-key" route="{{ route('users.changePassword', $user) }}" small="true" />

    @if ($user->status != 2)
        <x-backend.buttons.update icon="fas fa-ban" route="{{ route('users.block', $user) }}" small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
    @endif
    @if ($user->status == 2)
        <x-backend.buttons.update icon="fas fa-check" route="{{ route('users.unblock', $user) }}" small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
    @endif
    
    <x-backend.buttons.delete route="{{route('users.destroy', $user)}}" small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
</div>
