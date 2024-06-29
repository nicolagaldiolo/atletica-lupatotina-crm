<div class="text-end">
    @can('edit_'.$module_name)
    <x-backend.buttons.edit route='{!!route("$module_name.edit", $data)!!}' title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endcan
    <x-backend.buttons.show route='{!!route("$module_name.show", $data)!!}' title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
</div>
