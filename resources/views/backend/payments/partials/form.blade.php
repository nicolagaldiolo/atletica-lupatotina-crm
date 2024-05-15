@foreach ($athletes as $athlete)
    <div class="card card-accent-primary mb-3">
        <div class="card-header">
            {{ $athlete->fullname }}
        </div>
        <div class="card-body">
            @foreach ($athlete->fees as $fee)
                <div class="form-group mb-3">
                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" value="1" id="payments_{{ $athlete->id }}_{{ $fee->id }}" name="payments[{{ $athlete->id }}][{{ $fee->id }}]">
                        <label class="form-check-label" for="payments_{{ $athlete->id }}_{{ $fee->id }}">
                            <strong>{{ $fee->race->name }}</strong> - {{ $fee->name }} - {{ $fee->expired_at }} - {{ $fee->amount }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

{{--
<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <label for="brand_id">{{ __('Atleti') }}</label>

            <ul class="list-group">
                @foreach ($athletes as $athlete)
                    <li class="list-group-item">
                        <label class="form-check-label">
                            <input name="athletes[{{ $athlete->id }}]" class="form-check-input {{ $errors->has('athletes.' . $athlete->id) ? 'is-invalid' : '' }}" type="checkbox" value="{{ $athlete->id }}">
                            {{ $athlete->fullname }}
                            @if ($errors->has('athletes.' . $athlete->id))
                                <div style="display:block" class="invalid-feedback">{{ $errors->first('athletes.' . $athlete->id) }}</div>
                            @endif
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
--}}
