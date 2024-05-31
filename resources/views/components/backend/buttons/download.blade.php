@props(["route"=>"", "icon"=>"fas fa-download", "title"=>"", "small"=>"", "class"=>""])

<a href='{{$route}}'
    class='btn btn-outline-secondary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>