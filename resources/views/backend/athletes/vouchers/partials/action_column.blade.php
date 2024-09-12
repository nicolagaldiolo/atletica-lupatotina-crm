<div class="text-end">
    {{--@can('update', $fee)--}}
        <x-backend.buttons.edit route='{{ route("athletes.vouchers.edit", [$athlete, $voucher]) }}' small="true" />
    {{--@endcan--}}
    {{--@can('delete', $fee)--}}
        <x-backend.buttons.delete route='{{ route("athletes.vouchers.destroy", [$athlete, $voucher]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
</div>
