<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="brand_id">{{ __('Tariffa') }}</label>
            <select name="fee_id" class="form-control {{ $errors->has('fee_id') ? 'is-invalid' : '' }}">
                <option value="0">{{ __('Seleziona') }}</option>
                @foreach ($race->fees as $fee)
                    <option value="{{ $fee->id }}" @if ($fee->id == old('fee_id', $athleterace->fee_id)) selected @endif>
                        {{ $fee->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('fee_id'))
                <div class="invalid-feedback">{{ $errors->first('fee_id') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <div class="form-group mb-3">
                <label for="subscription_at">{{ __('Iscritto il') }}</label>
                @php
                    $subscription_at = $athleterace->subscription_at ? $athleterace->subscription_at : Carbon\Carbon::now();
                @endphp
                <input name="subscription_at" class="form-control {{ $errors->has('subscription_at') ? 'is-invalid' : '' }}" type="date" value="{{ old('subscription_at', App\Classes\Utility::dateFormatted($subscription_at)) }}">
                @if ($errors->has('subscription_at'))
                    <div class="invalid-feedback">{{ $errors->first('subscription_at') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($athletes->count())
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
@endif
