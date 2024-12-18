<option value="">{{ __('Seleziona') }}</option>
@foreach ($races as $race)
    <option value="{{ $race->id }}">{{ $race->name }}</option>    
@endforeach