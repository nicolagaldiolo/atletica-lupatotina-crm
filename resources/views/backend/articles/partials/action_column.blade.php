<div class="text-end">
    @can('update', $article)
        <x-backend.buttons.edit route='{{ route("articles.edit", $article) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can('delete', $article)
        <x-backend.buttons.delete route='{{ route("articles.destroy", $article) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" title="{{ __('Elimina') }}" data_token="{{csrf_token()}}"/>
    @endcan
</div>