<div class="text-end">
    @can((($raceType == App\Enums\RaceType::Race) ? 'registerPaymentRace' : (($raceType == App\Enums\RaceType::Track) ? 'registerPaymentTrack' : false)), $athleteFee)
        <x-backend.buttons.update icon="fas fa-edit" route='{{ route("athletes.fees.athletefee.edit", [$athleteFee->athlete_id,$raceType,$athleteFee->fee_id,$athleteFee]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
</div>
