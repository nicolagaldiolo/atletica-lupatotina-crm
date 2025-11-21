<style>
    table, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<table>
    <thead>
        <tr>
            <td style="font-weight:bold;">{{ __('Atleta') }}</td>
            <td style="font-weight:bold;">{{ __('Genere') }}</td>
            <td style="font-weight:bold;">{{ __('Gare partecipate') }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            @php $style = "background:" . App\Enums\GenderType::getColor($item['athlete']->gender) . "; font-weight:bold;" @endphp
            <tr>
                <td style="{{ $style }}">{{ $item['athlete']->fullname }}</td>
                <td style="{{ $style }}">{{ App\Enums\GenderType::getDescription($item['athlete']->gender) }}</td>
                <td style="{{ $style }}">{{ $item['races_count'] }}</td>
            </tr>
            @foreach($item['fees'] as $fee)
                <tr>
                    <td>{{ $fee->race->name }} ({{ $fee->name }})</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
