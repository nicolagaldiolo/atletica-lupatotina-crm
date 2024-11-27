<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="name">{{ __('Tipo') }}</label>
            <span class="text-danger">*</span>
            <select name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" @if($disabled) disabled @endif>
                @foreach(App\Enums\MemberType::asSelectArray() as $key => $value)
                    <option value="{{$key}}" @if($key == old('type', $athlete->type)) selected @endif>{{ __($value) }}</option>
                @endforeach
            </select>
            @if ($errors->has('type'))
                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="registration_number">{{ __('Tessera fidal') }}</label>
            <input name="registration_number" class="form-control {{ $errors->has('registration_number') ? 'is-invalid' : '' }}" type="text" value="{{ old('registration_number', $athlete->registration_number) }}" @if($disabled) disabled @endif>
            @if ($errors->has('registration_number'))
                <div class="invalid-feedback">{{ $errors->first('registration_number') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" value="{{ old('name', $athlete->name) }}" @if($disabled) disabled @endif>
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="surname">{{ __('Cognome') }}</label>
            <span class="text-danger">*</span>
            <input name="surname" class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}" type="text" value="{{ old('surname', $athlete->surname) }}" @if($disabled) disabled @endif>
            @if ($errors->has('surname'))
                <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="gender">{{ __('Genere') }}</label>
            <select name="gender" class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" @if($disabled) disabled @endif>
                @foreach(App\Enums\GenderType::asSelectArray() as $key => $value)
                    <option value="{{$key}}" @if($key == old('gender', $athlete->gender)) selected @endif>{{ __($value) }}</option>
                @endforeach
            </select>
            @if ($errors->has('gender'))
                <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="phone">{{ __('Telefono') }}</label>
            <input name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" value="{{ old('phone', $athlete->phone) }}" @if($disabled) disabled @endif>
            @if ($errors->has('phone'))
                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="email">{{ __('Email') }}</label>
            <input name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" value="{{ old('email', $athlete->email) }}" @if($disabled) disabled @endif>
            @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="address">{{ __('Indirizzo') }}</label>
            <input name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" value="{{ old('address', $athlete->address) }}" @if($disabled) disabled @endif>
            @if ($errors->has('address'))
                <div class="invalid-feedback">{{ $errors->first('address') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="zip">{{ __('Cap') }}</label>
            <input name="zip" class="form-control {{ $errors->has('zip') ? 'is-invalid' : '' }}" type="text" value="{{ old('zip', $athlete->zip) }}" @if($disabled) disabled @endif>
            @if ($errors->has('zip'))
                <div class="invalid-feedback">{{ $errors->first('zip') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="city">{{ __('Comune') }}</label>
            <input name="city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" value="{{ old('city', $athlete->city) }}" @if($disabled) disabled @endif>
            @if ($errors->has('city'))
                <div class="invalid-feedback">{{ $errors->first('city') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="birth_place">{{ __('Luogo di nascita') }}</label>
            <input name="birth_place" class="form-control {{ $errors->has('birth_place') ? 'is-invalid' : '' }}" type="text" value="{{ old('birth_place', $athlete->birth_place) }}" @if($disabled) disabled @endif>
            @if ($errors->has('birth_place'))
                <div class="invalid-feedback">{{ $errors->first('birth_place') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="birth_date">{{ __('Data di nascita') }}</label>
            <input name="birth_date" class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}" type="date" value="{{ old('birth_date', App\Classes\Utility::dateFormatted($athlete->birth_date)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('birth_date'))
                <div class="invalid-feedback">{{ $errors->first('birth_date') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-3">
        <div class="form-group mb-3">
            <label for="size">{{ __('Taglia') }}</label>
            <input name="size" class="form-control {{ $errors->has('size') ? 'is-invalid' : '' }}" type="text" value="{{ old('size', $athlete->size) }}" @if($disabled) disabled @endif>
            @if ($errors->has('size'))
                <div class="invalid-feedback">{{ $errors->first('size') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-3">
        <div class="form-group mb-3">
            <label for="10k">{{ __('10mila') }}</label>
            <input name="10k" class="form-control {{ $errors->has('10k') ? 'is-invalid' : '' }}" type="text" value="{{ old('10k', $athlete->{'10k'}) }}" @if($disabled) disabled @endif>
            @if ($errors->has('10k'))
                <div class="invalid-feedback">{{ $errors->first('10k') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-3">
        <div class="form-group mb-3">
            <label for="half_marathon">{{ __('Mezza maratona') }}</label>
            <input name="half_marathon" class="form-control {{ $errors->has('half_marathon') ? 'is-invalid' : '' }}" type="text" value="{{ old('half_marathon', $athlete->half_marathon) }}" @if($disabled) disabled @endif>
            @if ($errors->has('half_marathon'))
                <div class="invalid-feedback">{{ $errors->first('half_marathon') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-3">
        <div class="form-group mb-3">
            <label for="marathon">{{ __('Maratona') }}</label>
            <input name="marathon" class="form-control {{ $errors->has('marathon') ? 'is-invalid' : '' }}" type="text" value="{{ old('marathon', $athlete->marathon) }}" @if($disabled) disabled @endif>
            @if ($errors->has('marathon'))
                <div class="invalid-feedback">{{ $errors->first('marathon') }}</div>
            @endif
        </div>
    </div>
</div>

@push('after-styles')
@endpush

@push ('after-scripts')
@endpush
