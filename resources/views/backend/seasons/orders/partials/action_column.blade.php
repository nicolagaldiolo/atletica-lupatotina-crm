<div class="text-end">
    @can('update', [$order, new App\Models\Athlete()])
        <x-backend.buttons.show route='{{ route("seasons.orders.edit", [$season, $order]) }}' icon="fas fa-edit" small="true" title="{{ __('Modifica') }}"/>    
    @endcan
</div>
