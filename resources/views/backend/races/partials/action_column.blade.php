<div class="text-end">
    <x-backend.buttons.download route='{{ route("races.subscriptions-list", $race) }}' small="true" />

    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("races.athletes", $race) }}' small="true" />
    @endcan
</div>
