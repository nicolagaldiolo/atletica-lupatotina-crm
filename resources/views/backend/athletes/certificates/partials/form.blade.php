<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="name">{{ __('Scadenza') }}</label>
            <span class="text-danger">*</span>
            <input name="expires_on" class="form-control {{ $errors->has('expires_on') ? 'is-invalid' : '' }}" type="date" value="{{ old('expires_on', App\Classes\Utility::dateFormatted($certificate->expires_on)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('expires_on'))
                <div class="invalid-feedback">{{ $errors->first('expires_on') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group mb-3">
            <label for="document">{{ __('Certificato') }}</label>
            <input name="document" class="form-control {{ $errors->has('document') ? 'is-invalid' : '' }}" type="file" value="" @if($disabled) disabled @endif>
            @if ($errors->has('document'))
                <div class="invalid-feedback">{{ $errors->first('document') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <h1>Qui mostro il file</h1>
    </div>
</div>
