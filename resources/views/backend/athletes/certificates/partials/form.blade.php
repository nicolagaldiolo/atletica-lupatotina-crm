<div class="row">
    
    <div class="col-12 col-sm-2">
        <div class="form-group mb-3">
            <label for="is_current">{{ __('Certificato corrente') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_current" class="form-item" type="hidden" checked value="0">
                <input class="form-check-input form-item {{ $errors->has('is_current') ? 'is-invalid' : '' }}" type="checkbox" name="is_current" {{ old('is_current', $certificate->is_current) ? 'checked' : "" }} value="1">
                @if ($errors->has('is_current'))
                    <div class="invalid-feedback">{{ $errors->first('is_current') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-5">
        <div class="form-group mb-3">
            <label for="name">{{ __('Scadenza') }}</label>
            <span class="text-danger">*</span>
            <input name="expires_on" class="form-control {{ $errors->has('expires_on') ? 'is-invalid' : '' }}" type="date" value="{{ old('expires_on', App\Classes\Utility::dateFormatted($certificate->expires_on)) }}" @if($disabled) disabled @endif>
            @if ($errors->has('expires_on'))
                <div class="invalid-feedback">{{ $errors->first('expires_on') }}</div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-5">
        <div class="form-group mb-3">
            <label for="document">{{ __('File') }}</label>
            <input name="document" class="form-control {{ $errors->has('document') ? 'is-invalid' : '' }}" type="file" value="" @if($disabled) disabled @endif>
            @if ($errors->has('document'))
                <div class="invalid-feedback">{{ $errors->first('document') }}</div>
            @endif

            @if($certificate->status && $certificate->status['url_download'])
                <a class="btn btn-primary btn-sm mt-2" href="{{ $certificate->status['url_download'] }}" target="_blank">
                    <i class="fa fa-download"></i>
                    {{ __('Scarica certificato') }}
                </a>
            @endif
        </div>
    </div>
</div>
