<div class="text-end">
    @can('update', $season)
        <x-backend.buttons.edit route='{{ route("seasons.edit", $season) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan

    @can('viewAny', [App\Models\Order::class, new App\Models\Athlete()])
        <x-backend.buttons.edit route='{{ route("seasons.orders.index", $season) }}' icon="fas fa-coins" title="{{ 'Ordini' }}" small="true" />
    @endcan

    @can('delete', $season)
        <x-backend.buttons.delete route='{{ route("seasons.destroy", $season) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
    
</div>