<table>
    <thead>
        <tr>
            <td>{{ __('Atleta') }}</td>
            <td>{{ __('Data') }}</td>
            <td>{{ __('Tipo') }}</td>
            <td>{{ __('Evento') }}</td>
            <td>{{ __('Evento') }}</td>
            <td>{{ __('Voucher') }}</td>
            <td>{{ __('Penalità') }}</td>
            <td>{{ __('Importo') }}</td>
        </tr>
    </thead>
    <tbody>
        @php
            $gran_total = 0;
        @endphp
        @foreach($data as $athlete)
            @php
                $partial_total = 0;
            @endphp
            @foreach($athlete as $row)
                <tr>
                    <td>{{ $row['athlete_name'] }}</td>
                    <td>@date($row['created_at'])</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['event'] }}</td>
                    <td>@money($row['event_amount'])</td>
                    <td>@money($row['voucher'])</td>
                    <td>@money($row['penalty'])</td>
                    <td>@money($row['amount'])</td>
                </tr>
                @php $partial_total += $row['amount'] @endphp
            @endforeach
            <tr>
                <td colspan="7" class="text-end text-right">{{ $row['athlete_name'] }} {{ __(' - totale da incassare') }}</td>
                <td>@money($partial_total)</td>
            </tr>
            @php $gran_total += $partial_total @endphp
        @endforeach
        <tr>
            <td colspan="7" class="text-end text-right">{{ __('Totale da incassare') }}</td>
            <td>@money($gran_total)</td>
        </tr>
    </tbody>
</table>
