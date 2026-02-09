<div class="text-end">
    @can('update', $size)
        <x-backend.buttons.edit route='{{ route("sizes.edit", $size) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can('delete', $size)
        <x-backend.buttons.delete route='{{ route("sizes.destroy", $size) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" title="{{ __('Elimina') }}" data_token="{{csrf_token()}}"/>
    @endcan
</div>