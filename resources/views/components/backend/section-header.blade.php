@props(["data"=>"", "toolbar"=>"", "title"=>"", "subtitle"=>"", "module_name"=>"", "module_title"=>"", "module_icon"=>"", "module_action"=>""])

<div class="d-flex justify-content-between">
    <div>
        @if($slot != "")
            <h4 class="card-title mb-0">
                {{ $slot }}
            </h4>
        @endif

        @if($subtitle)
            <div class="small text-medium-emphasis">
                {{ $subtitle }}
            </div>
        @endif
    </div>
    @if($toolbar)
        <div class="btn-toolbar d-block text-end" role="toolbar" aria-label="Toolbar with buttons">
            {{ $toolbar }}
        </div>
    @endif
</div>
