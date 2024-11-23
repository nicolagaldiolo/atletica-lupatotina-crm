<div class="text-end">
    @can('registerPayment', $athleteFee)
        <x-backend.buttons.update icon="fas fa-edit" route='{{ route("athletes.fees.athletefee.edit", [$athleteFee->athlete_id,$athleteFee->fee_id,$athleteFee]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
</div>
