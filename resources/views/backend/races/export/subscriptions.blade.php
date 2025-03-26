<table>
    <thead>
        <tr>
            <td>{{ __('Nome')}}</td>
            <td>{{ __('Cognome') }}</td>
            <td>{{ __('Codice fiscale') }}</td>
            <td>{{ __('Genere') }}</td>
            <td>{{ __('Telefono') }}</td>
            <td>{{ __('Email') }}</td>
            <td>{{ __('Indirizzo') }}</td>
            <td>{{ __('Cap') }}</td>
            <td>{{ __('Città') }}</td>
            <td>{{ __('Luogo di nascita') }}</td>
            <td>{{ __('Data di nascita') }}</td>
            <td>{{ __('Tessera Fidal') }}</td>
            <td>{{ __('Scadenza certificato') }}</td>
            <td>{{ __('Quota iscrizione') }}</td>
            <td>{{ __('Importo quota iscrizione') }}</td>
            <td>{{ __('Scadenza quota iscrizione') }}</td>
            <td>{{ __('Importo') }}</td>
            <td>{{ __('Voucher') }}</td>
            <td>{{ __('Penalità') }}</td>
            <td>{{ __('Pagato il') }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->athlete->name }}</td>
                <td>{{ $subscription->athlete->surname }}</td>
                <td>{{ $subscription->athlete->tax_id }}</td>
                <td>{{ App\Enums\GenderType::getDescription($subscription->athlete->gender) }}</td>
                <td>{{ $subscription->athlete->phone }}</td>
                <td>{{ $subscription->athlete->email }}</td>
                <td>{{ $subscription->athlete->address }}</td>
                <td>{{ $subscription->athlete->zip }}</td>
                <td>{{ $subscription->athlete->city }}</td>
                <td>{{ $subscription->athlete->birth_place }}</td>
                <td>
                    @if($subscription->athlete->birth_date)
                        @date($subscription->athlete->birth_date)
                    @else
                        &nbsp; 
                    @endif
                </td>
                <td>{{ $subscription->athlete->registration_number }}</td>
                <td>
                    @if($subscription->athlete->certificate && $subscription->athlete->certificate->expires_on)
                        @date($subscription->athlete->certificate->expires_on)
                    @else
                        &nbsp; 
                    @endif
                </td>
                <td>{{ $subscription->fee->name }}</td>
                <td>@value($subscription->fee->amount)</td>
                <td>
                    @if($subscription->fee->expired_at)
                        @date($subscription->fee->expired_at)
                    @else
                        &nbsp; 
                    @endif
                </td>
                <td>@value($subscription->custom_amount)</td>
                <td>@if($subscription->voucher && $subscription->voucher->type == App\Enums\VoucherType::Credit)@value($subscription->voucher->amount_calculated)@else &nbsp; @endif</td>
                <td>@if($subscription->voucher && $subscription->voucher->type == App\Enums\VoucherType::Penalty)@value($subscription->voucher->amount_calculated)@else &nbsp; @endif</td>
                <td>
                    @if($subscription->payed_at) 
                        @date($subscription->payed_at) 
                    @else 
                        &nbsp; 
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
