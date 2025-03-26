<style>
    table, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<table>
    <thead>
        <tr>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Atleta') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Data') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Tipo') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Evento') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Evento') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Voucher') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Penalità') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ __('Importo') }}</td>
        </tr>
    </thead>
    <tbody>
        @php
            $gran_total = 0;
        @endphp
        @foreach($data as $athlete)
            <tr>
                <td style="background:#9FC5E8; font-weight:bold;" colspan="8">{{ $athlete[0]['athlete_name'] }}</td>
            </tr>
            @php
                $partial_total = 0;
            @endphp
            @foreach($athlete as $row)
                <tr>
                    <td>{{ $row['athlete_name'] }}</td>
                    <td>@date($row['created_at'])</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['event'] }}</td>
                    <td>{{ $row['event_amount'] }}</td>
                    <td>{{ $row['voucher'] }}</td>
                    <td>{{ $row['penalty'] }}</td>
                    <td>{{ $row['amount'] }}</td>
                </tr>
                @php $partial_total += $row['amount'] @endphp
            @endforeach
            <tr>
                <td style="background:#CFE2F3; font-weight:bold;" colspan="7">{{ $row['athlete_name'] }} {{ __(' - totale da incassare') }}</td>
                <td style="background:#CFE2F3; font-weight:bold;">{{ $partial_total }}</td>
            </tr>
            @php $gran_total += $partial_total @endphp
        @endforeach
        <tr>
            <td style="background:#3D86C6; font-weight:bold;" colspan="7">{{ __('Totale da incassare') }}</td>
            <td style="background:#3D86C6; font-weight:bold;">{{ $gran_total }}</td>
        </tr>
    </tbody>
</table>
