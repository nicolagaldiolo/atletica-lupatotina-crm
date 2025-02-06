<style>
    table, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

@foreach ($proceeds as $key=>$proc)
    <table>
        <thead>
            <tr>
                @if($key == '0000-00')
                <td style="background:#CFE2F3; font-weight:bold; text-align:center;" colspan="4">{{ __('Incassato da scaricare')}}</td>
                @else
                <td style="background:#D5F1DE; font-weight:bold; text-align:center;" colspan="4">{{ __('Scaricato il ')}} {{ $key }}</td>
                @endif
            </tr>
            <tr>
                <td style="font-weight:bold;">{{ __('Socio') }}</td>
                <td style="font-weight:bold;">{{ __('Gara') }}</td>
                <td style="font-weight:bold;">{{ __('Pagamento') }}</td>
                <td style="font-weight:bold;">{{ __('Importo') }}</td>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($proc as $item)
                <tr>
                    <td>{{ $item->athlete->fullname }}</td>
                    <td>{{ $item->fee->race->name }}</td>
                    <td>@date($item->payed_at)</td>
                    <td>{{ $item->custom_amount }}</td>
                    <td style="font-weight:bold;" colspan="4">&nbsp;</td>
                </tr>
                @php $total += $item->custom_amount; @endphp
            @endforeach
            <tr>
                <td style="font-weight:bold;" colspan="4">{{ $total }}</td>
            </tr>
        </tbody>
    </table>
@endforeach