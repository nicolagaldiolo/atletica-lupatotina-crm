@php
    $layout = $layout ?? null;
    $disabled = $disabled ?? false;
@endphp

@if($layout == 'datatable')

<div class="text-end">
    @can('view', [$order, $athlete])
        <x-backend.buttons.show route='{{ route("athletes.orders.show", [$athlete, $order]) }}' small="true" title="{{ __('Visualizza') }}"/>    
    @endcan
    @can('update', [$order, $athlete])
        <x-backend.buttons.edit route='{{ route("athletes.orders.edit", [$athlete, $order]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can('delete', [$order, $athlete])
        <x-backend.buttons.delete route='{{ route("athletes.orders.destroy", [$athlete, $order]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
@endif

@if($layout == 'form')
    @can('delete', [$order, $athlete])
        @if($order->id)
            <x-backend.buttons.delete route='{{ route("athletes.orders.destroy", [$athlete, $order]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
        @endif
    @endcan

    <div class="float-end">
        @can('viewAny', [App\Models\Order::class, $athlete])
            <x-backend.buttons.return route='{{ route("athletes.orders.index", [$athlete]) }}' small="true">{{ __('Indietro') }}</x-backend.buttons.return>
        @endcan
        @if($disabled == false)
            @can('create', [App\Models\Order::class, $athlete])
                <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
            @endcan
        @endif
    </div>
@endif