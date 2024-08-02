<table>
    <thead>
        <tr>
            <td>{{ __('Nome') }}</td>
            <td>{{ __('Cognome') }}</td>
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
            <td>{{ __('Scadenza quota iscrizione') }}</td>
            <td>{{ __('Importo quota iscrizione') }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->athlete->name }}</td>
                <td>{{ $subscription->athlete->surname }}</td>
                <td>{{ App\Enums\GenderType::getDescription($subscription->athlete->gender) }}</td>
                <td>{{ $subscription->athlete->phone }}</td>
                <td>{{ $subscription->athlete->email }}</td>
                <td>{{ $subscription->athlete->address }}</td>
                <td>{{ $subscription->athlete->zip }}</td>
                <td>{{ $subscription->athlete->city }}</td>
                <td>{{ $subscription->athlete->birth_place }}</td>
                <td>@date($subscription->athlete->birth_date)</td>
                <td>{{ $subscription->athlete->registration_number }}</td>
                <td>@date($subscription->athlete->certificate->expires_on)</td>
                <td>{{ $subscription->fee->name }}</td>
                <td>@date($subscription->fee->expired_at)</td>
                <td>@money($subscription->fee->amount)</td>
            </tr>
        @endforeach
    </tbody>
</table>
