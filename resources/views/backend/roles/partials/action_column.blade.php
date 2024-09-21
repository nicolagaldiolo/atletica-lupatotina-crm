<div class="text-end">
    @can('update', $role)
        <x-backend.buttons.edit route='{{ route("roles.edit", $role) }}' small="true" />
    @endcan
</div>
