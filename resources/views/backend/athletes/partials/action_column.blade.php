<div class="text-end">
    @can('update', $athlete)
        <x-backend.buttons.edit route='{{ route("backend.athletes.edit", $athlete) }}' small="true" />
    @endcan
    @can('delete', $athlete)
        <x-backend.buttons.delete route='{{ route("backend.athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
