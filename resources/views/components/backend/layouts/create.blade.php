@props(["data"=>"", "module_name", "module_path", "module_title"=>"", "module_icon"=>"", "module_action"=>""])
<div class="card">
    @if ($slot != "")
    <div class="card-body">
        {{ $slot }}
    </div>
    @else
    <div class="card-body">
        
        <x-backend.section-header :module_name="$module_name" :module_title="$module_title" :module_icon="$module_icon" :module_action="$module_action" />

        <div class="row mt-4">
            <div class="col">
                {{ html()->form('POST', route("$module_name.store"))->class('form')->acceptsFiles()->open() }}

                @include ("$module_path.$module_name.form")

                <div class="row">
                    <div class="col-6">
                        <x-backend.buttons.create>Create</x-backend.buttons.create>
                    </div>
                    <div class="col-6">
                        <div class="float-end">
                            <x-backend.buttons.cancel />
                        </div>
                    </div>
                </div>

                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
    @endif
</div>