<div class="text-end">
    <x-backend.buttons.download route='{{ route("backend.races.subscriptions-list", $race) }}' small="true" />

    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("backend.races.athletes", $race) }}' small="true" />
    @endcan
</div>
