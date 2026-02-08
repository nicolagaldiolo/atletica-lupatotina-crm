@props(["route"=>"#", "title"=>"", "type"=>"", "canurl" => true])

@if($type)
<li class="breadcrumb-item active">
    <span>
        {{ $slot }}
    </span>
</li>
@else
<li class="breadcrumb-item">
    @if($canurl)
        <a @if($route)href='{{$route}}' @endif>
            {{ $slot }}
        </a>
    @else
        <span>
            {{ $slot }}
        </span>
    @endif
</li>
@endif
