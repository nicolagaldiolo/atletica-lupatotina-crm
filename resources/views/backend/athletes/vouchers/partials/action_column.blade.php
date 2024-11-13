<div class="text-end">
    @can('update', $voucher)
        <x-backend.buttons.edit route='{{ route("athletes.vouchers.edit", [$athlete, $voucher]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can('delete', $voucher)
        <x-backend.buttons.delete route='{{ route("athletes.vouchers.destroy", [$athlete, $voucher]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
