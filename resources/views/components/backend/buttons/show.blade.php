@props(["route"=>"", "icon"=>"fas fa-eye", "title"=>"", "small"=>"", "class"=>"", "count"=>null ])

@if($route)
<a href='{{$route}}'
    class='btn btn-outline-secondary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
    <span class="badge text-bg-secondary">{{ $count }}</span>
</a>
@else
<button type="submit"
    class='btn btn-outline-secondary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
    <span class="badge text-bg-secondary">{{ $count }}</span>
</button>
@endif
