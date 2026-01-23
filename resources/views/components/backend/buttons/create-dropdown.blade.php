@props(["icon"=>"fas fa-plus", "title"=>"Create", "small"=>"", "class"=>"", "label" => "Dropdown"])

<div class="dropdown">
  <button 
    class="btn btn-primary m-1 {{($small=='true')? 'btn-sm' : ''}} dropdown-toggle {{ $class }}" 
    type="button" 
    data-coreui-toggle="dropdown" 
    aria-expanded="false"
    title="{{ __($title) }}">
    <i class="{{$icon}}"></i>
    {{ $label }}
  </button>
  <ul class="dropdown-menu">
    {{ $slot }}
  </ul>
</div>