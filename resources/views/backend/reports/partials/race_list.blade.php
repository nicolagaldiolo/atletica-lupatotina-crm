<option value="">{{ __('Seleziona gara') }}</option>
@foreach ($races as $race)
    <option value="{{ $race->id }}">{{ $race->name }}</option>    
@endforeach