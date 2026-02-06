<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group mb-3">
            <label for="is_active">{{ __('Attivo') }}</label>
            <span class="text-danger">*</span>
            <div class="form-check form-switch form-switch-lg">
                <input name="is_active" type="hidden" checked value="0">
                <input class="form-check-input {{ $errors->has('is_active') ? 'is-invalid' : '' }}"
                    type="checkbox" name="is_active"
                    {{ old('is_active', $article->is_active) ? 'checked' : '' }} value="1">
                @if ($errors->has('is_active'))
                    <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>

        <input name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="hidden"
            value="{{ $article->type }}">

        <div class="form-group mb-3">
            <label for="name">{{ __('Nome') }}</label>
            <span class="text-danger">*</span>
            <input name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                type="text" value="{{ old('name', $article->name) }}">
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
        <div class="form-group mb-3">
            <label for="name">{{ __('Prezzo') }}</label>
            <span class="text-danger">*</span>
            <input name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                type="number" step=".01" value="{{ old('price', $article->price) }}">
            @if ($errors->has('price'))
                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="name">{{ __('Quantità') }}</label>
            <span class="text-danger">*</span>

            @if ($article->type == \App\Enums\ArticleType::Simple)
                <input name="quantity" class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}"
                    type="number" step="1" min="0"
                    value="{{ old('quantity', $article->quantity ? $article->quantity : 0) }}">
                @if ($errors->has('quantity'))
                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                @endif
            @elseif($article->type == \App\Enums\ArticleType::Variants)
                <div class="card">
                    <div class="card-body">
                        @foreach (App\Enums\Sizes::asSelectArray() as $key => $value)
                        <div class="input-group mb-3">
                            <span style="width: 50px;" class="input-group-text" id="basic-addon1"><strong>{{ Str::upper($value) }}</strong></span>
                            <input name="variants[{{ $key }}]"
                                    class="form-control {{ $errors->has('variants.' . $key) ? 'is-invalid' : '' }}"
                                    type="number" step="1" min="0"
                                    value="{{ old('variants.' . $key, $article->variants[$key] ?? 0) }}">
                                @if ($errors->has('variants.' . $key))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('variants.' . $key) }}
                                    </div>
                                @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <input name="images[]" id="upload_images" type="file" multiple>
    </div>
</div>

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-fileinput/fileinput.min.css') }}">
@endpush

@push('after-scripts')

    <script type="text/javascript" src="{{ asset('vendor/bootstrap-fileinput/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootstrap-fileinput/theme.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootstrap-fileinput/it.js') }}"></script>

    <script type="text/javascript">
        var isDefaultClass = 'btn-primary';
        var isNotDefaultClass = 'btn-outline-secondary';

        var isDisabledClass = 'btn-danger';
        var isNotDisabledClass = 'btn-outline-secondary';

        function fileinputSetDefaultImage(id){
            $('.kv-cust-btn-default').each(function(i, item){
                if($(item).data('id') == id){
                    $(item).removeClass(isNotDefaultClass).addClass(isDefaultClass);
                }else{
                    $(item).removeClass(isDefaultClass).addClass(isNotDefaultClass);
                }
            });
        }

        $(document).on('click', '.kv-cust-btn-default', function() {
            const success_message = 'Immagine default aggiornata';
            const error_message = 'Errore aggiornamento immagine default';

            $el = $(this);
            $.post({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: $(this).data('url'),
                type: 'POST'
            }).done(function(data) {
                fileinputSetDefaultImage($el.data('id'));
                iziToast.success({
                    message: success_message
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                iziToast.error({
                    message: error_message
                });
            });
        });

        $(document).on('click', '.kv-cust-btn-disable', function() {
            const success_message = 'Immagine aggiornata correttamente';
            const error_message = 'Errore aggiornamento immagine';

            $el = $(this);
            $.post({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: $(this).data('url'),
                type: 'POST'
            }).done(function(data) {
                if(data.is_disabled){
                    $el.removeClass(isNotDisabledClass).addClass(isDisabledClass);
                }else{
                    $el.removeClass(isDisabledClass).addClass(isNotDisabledClass);
                }

                iziToast.success({
                    message: success_message
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                iziToast.error({
                    message: error_message
                });
            });

        });

        var otherActionButtons = [
            '<button type="button" class="btn btn-sm btn-kv kv-cust-btn-default {TAG_CSS_INIT} {DEFAULT_CLASS}" title="Edit" data-id="{ELEMENT_ID}" data-url="{DEFAULT_URL}"><i class="fas fa-home"></i></button>',
            '<button type="button" class="btn btn-sm btn-kv kv-cust-btn-disable {TAG_CSS_INIT} {DISABLE_CLASS}" title="Edit" data-id="{ELEMENT_ID}" data-url="{DISABLE_URL}"><i class="fas fa-eye-slash"></i></button>'
        ];

        var initialPreviewThumbTags = ({!! $article->images->pluck('info') !!}).map(function(item){
            return {
                "{ELEMENT_ID}": (item && item.id) ? item.id : null,
                "{DEFAULT_CLASS}": (item && item.is_default) ? isDefaultClass : isNotDefaultClass,
                "{DEFAULT_URL}": (item && item.set_default_url) ? item.set_default_url : null,
                "{DISABLE_CLASS}": (item && item.is_disabled) ? isDisabledClass : isNotDisabledClass,
                "{DISABLE_URL}": (item && item.set_disable_url) ? item.set_disable_url : null,
                '{TAG_CSS_INIT}': ''
            };
        });

        $('#upload_images').fileinput({
            allowedFileExtensions: ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'],
            uploadAsync: false,
            maxFileSize: 0,
            language: 'it',
            theme: 'fas',
            previewThumbTags: {
                '{ELEMENT_ID}' : '',
                '{DEFAULT_CLASS}': '',
                '{DEFAULT_URL}': '',
                '{DISABLE_CLASS}': '',
                '{DISABLE_URL}': '',
                '{TAG_CSS_INIT}': 'kv-hidden'  // hide the initial input
            },
            initialPreviewAsData: true,
            initialPreview: {!! $article->images->pluck('public_url') !!},
            initialPreviewConfig: {!! $article->images->pluck('info') !!},
            initialPreviewThumbTags: initialPreviewThumbTags,
            overwriteInitial: false,
            otherActionButtons: otherActionButtons.join(" "),
            ajaxDeleteSettings: {
                type: 'DELETE' // This should override the ajax as $.ajax({ type: 'DELETE' })
            },
            deleteExtraData: {
                _token: '{{ csrf_token() }}'
            }
        }).on("filepredelete", function(jqXHR) {
            var abort = true;
            if (confirm('{{ __('Sei sicuro di voler eliminare questa immagine?') }}')) {
                abort = false;
            }
            return abort;
        }).on('filedeleted', function(event, key, jqXHR, data) {
            if(jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.default_id){
                fileinputSetDefaultImage(jqXHR.responseJSON.default_id)
            }
        })
        .on('filesorted', function(event, params) {
            @if ($article && $article->id)
            let $ids = params.stack.map(i => i['id']);
            $.post({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '{{ route('articles.sortImages', [$article->id]) }}',
                type: 'POST',
                data: {ids: $ids}
            }).done(function(data) {
                if (data.type == 'success') {
                    iziToast.success({
                        message: data.message
                    });
                } else if (data.type == 'warning') {
                    iziToast.warning({
                        message: data.message
                    });
                } else {
                    iziToast.error({
                        message: data.message
                    });
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                iziToast.error({
                    message: jqXHR.responseJSON ? jqXHR.responseJSON.message : textStatus
                });
            });
            @endif
        });

    </script>

@endpush
