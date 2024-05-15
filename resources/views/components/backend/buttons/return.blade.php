@props(["route"=>"#", "icon"=>"", "title"=> "", "small"=>"", "class"=>""])

<a href='{{$route}}'
    class='btn btn-outline-secondary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    @if($icon) <i class="{{$icon}}"></i> @endif
    {{ $slot }}
</a>
