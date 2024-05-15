{{--
<table>
    <thead>

        @php
            $rows_number = count($config->sub_actions_table_header);

        @endphp
        @foreach($config->sub_actions_table_header as $row)
        <tr>
            @if ($loop->index == 0)
                <td rowspan="{{ $rows_number }}" style="font-weight: bold; text-align: center; border: 1px solid #AAAAAA;">{{ __('MACROAREA') }} </td>
            @endif
            @foreach($row as $cell)
                @if(!$cell['sub_action']->only_project || auth()->user()->can('explodeBudgetSpecialActions', App\Models\Tenant\Project::class))
                    <td rowspan="{{ $cell['is_leaf'] == '1' ? ($rows_number - $loop->parent->index ) : 1 }}" colspan="{{ $cell['number_of_children_leaf'] }}" style="background-color: {{ $cell['sub_action']['color'] }}; font-weight: bold; text-align: center; border: 1px solid #AAAAAA;">
                        {{ $cell['sub_action']['code'] }}
                    </td>
                @endif
            @endforeach
            @if ($loop->index == 0)
                <td rowspan="{{ $rows_number }}" style="font-weight: bold; text-align: center; border: 1px solid #AAAAAA;">{{ __('Totale') }}</td>
            @endif
        </tr>
        @endforeach
    </thead>
    <tbody>
        @php $summaryTotal = 0; @endphp
        @foreach($config->areas->all() as $area)
        <tr>
            <td style="border: 1px solid #AAAAAA;">{{ $area->name }}</td>
            @php $valueArea = 0; @endphp
            @foreach($config->leaf_sub_actions as $subAction)
                @if(!$subAction->only_project || auth()->user()->can('explodeBudgetSpecialActions', App\Models\Tenant\Project::class))
                    @php $value = $rows[$area->id][$subAction->id]['value'] ?? 0; @endphp
                    @php $valueArea = $valueArea + $value; @endphp
                    <td style="background-color: {{ $subAction->color }}; border: 1px solid #AAAAAA;">{{ $value != 0 ? $value : '' }}</td>
                @endif
            @endforeach
            @php $summaryTotal = $summaryTotal + $valueArea; @endphp
            <td style="font-weight: bold; font-weight: bold; border: 1px solid #AAAAAA;">{{ $valueArea != 0 ? $valueArea : '' }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            @foreach($config->actions->all() as $action)
                @php $valueAction = 0; @endphp
                @if(!$action->only_project || auth()->user()->can('explodeBudgetSpecialActions', App\Models\Tenant\Project::class))
                    @foreach($action->subActions->all() as $subAction)
                        @php $value = $rows[$area->id][$subAction->id]['value'] ?? 0; @endphp
                        @php
                            $valueAction = $valueAction + $value;
                        @endphp
                    @endforeach
                    <td style="text-align:right; background-color: {{ $action->color }}; font-weight: bold;" colspan="{{ count($action->subActions) }}">Totale: @if($valueAction != 0) @money($valueAction) @else 0 @endif</td>
                @endif
            @endforeach
            <td>&nbsp;</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold; border: 1px solid #AAAAAA;">{{ __('Totale') }}</td>
            @foreach($config->leaf_sub_actions as $subAction)
                @if(!$subAction->only_project || auth()->user()->can('explodeBudgetSpecialActions', App\Models\Tenant\Project::class))
                    @php
                        $totalSubAction = collect($rows)->reduce(function($total, $item) use($subAction){
                            return $total + ((array_key_exists($subAction->id, $item) && array_key_exists('value', $item[$subAction->id])) ? $item[$subAction->id]['value'] : 0);
                        }, 0);
                    @endphp
                    <td style="text-align:right; font-weight: bold; border: 1px solid #AAAAAA;">{{ $totalSubAction != 0 ? $totalSubAction : '' }}</td>
                @endif
            @endforeach
            <td style="text-align:right; font-weight: bold; border: 1px solid #AAAAAA;">{{ $summaryTotal }}</td>
        </tr>

    </tbody>
</table>
--}}

<table>
    <thead>
        <tr>
            <td>Atleta</td>
            <td>Data</td>
            <td>Tipo</td>
            <td>Movimento</td>
            <td>Totale</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['athlete_name'] }}</td>
                <td>{{ $row['created_at'] }}</td>
                <td>{{ $row['type'] }}</td>
                <td>{{ $row['movement_name'] }}</td>
                <td>{{ $row['amount'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
