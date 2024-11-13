<div class="text-end">
    @can('update', $certificate)
        <x-backend.buttons.edit route='{{ route("athletes.certificates.edit", [$athlete, $certificate]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can('delete', $certificate)
        <x-backend.buttons.delete route='{{ route("athletes.certificates.destroy", [$athlete, $certificate]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
