<div class="text-end">
    {{--@can('update', $fee)--}}
        <x-backend.buttons.edit route='{{ route("backend.athletes.certificates.edit", [$athlete, $certificate]) }}' small="true" />
    {{--@endcan--}}
    {{--@can('delete', $fee)--}}
        <x-backend.buttons.delete route='{{ route("backend.athletes.certificates.destroy", [$athlete, $certificate]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
</div>
