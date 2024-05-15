@props([
    "route"=>"",
    "icon"=>"fas fa-undo",
    "title" => "",
    "small"=>"",
    "class"=>"",
    "data_confirm"=>"",
    "data_method"=>"",
    "data_token"=>"",
])


@if($route)
<a href='{{$route}}'
    @if($data_token) data-token='{{ $data_token }}' @endif
    @if($data_method) data-method='{{ $data_method }}' @endif
    @if($data_confirm) data-confirm='{{ $data_confirm }}' @endif
    class='btn btn-outline-info {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    @if($data_token) data-token='{{ $data_token }}' @endif
    @if($data_method) data-method='{{ $data_method }}' @endif
    @if($data_confirm) data-confirm='{{ $data_confirm }}' @endif
    class='btn btn-outline-info {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
