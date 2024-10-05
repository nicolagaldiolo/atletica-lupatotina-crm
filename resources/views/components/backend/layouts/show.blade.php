@props(["data"=>"", "module_name", "module_path", "module_title"=>"", "module_icon"=>"", "module_action"=>""])
<div class="card">
    @if ($slot != "")
    <div class="card-body">
        {{ $slot }}
    </div>
    @else
    <div class="card-body">

        <x-backend.section-header :data="$data" :module_name="$module_name" :module_title="$module_title" :module_icon="$module_icon" :module_action="$module_action" />

        <div class="row mt-4">
            <div class="col-12">

                <x-backend.section-show-table :data="$data" :module_name="$module_name" />

            </div>
        </div>
    </div>
    @endif
</div>