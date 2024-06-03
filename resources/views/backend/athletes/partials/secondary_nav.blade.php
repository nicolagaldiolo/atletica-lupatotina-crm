<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route("backend.athletes.index") }}">
                    <i class="far fa-times-circle"></i>
                    {{ __('Chiudi') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route("backend.athletes.edit", $athlete) }}">{{ __('Anagrafica') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route("backend.athletes.certificates.index", $athlete) }}">{{ __('Dati sanitari') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route("backend.athletes.races.index", $athlete) }}">{{ __('Iscrizioni') }}</a>
            </li>
        </ul>
    </div>
    </div>
</nav>