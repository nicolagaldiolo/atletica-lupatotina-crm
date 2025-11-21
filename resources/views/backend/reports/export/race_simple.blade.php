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
            <td style="font-weight:bold;">{{ __('Gare partecipate') }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item['athlete']->fullname }}</td>
                <td>{{ $item['races_count'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
