<div class="text-end">
    @can('registerPayment', $athleteFee)
        <x-backend.buttons.update icon="fa-solid fa-coins" route='{{ route("athletes.payFee", $athleteFee) }}' small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
    @endcan
    @can('subscribe', $athleteFee)
        <x-backend.buttons.delete route='{{ route("athletes.destroySubscription", $athleteFee) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
