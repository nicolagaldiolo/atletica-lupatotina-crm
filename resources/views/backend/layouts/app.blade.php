<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ language_direction() }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
    <!-- Aggiunta icona per dispositivi mobile -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/apple-touch-icon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <script src="{{ asset('vendor/jquery/jquery-3.6.4.min.js') }}"></script>

    @vite(['resources/sass/app-backend.scss', 'resources/js/app-backend.js'])

    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    @stack('after-styles')

</head>

<body>
    <!-- Sidebar -->
    @include('backend.includes.sidebar')
    <!-- /Sidebar -->

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <!-- Header -->
        @include('backend.includes.header')
        <!-- /Header -->

        <div class="body flex-grow-1">
            <div class="container-fluid">

                @include('flash::message')

                <!-- Errors block -->
                @include('backend.includes.errors')
                <!-- / Errors block -->

                <!-- Main content block -->
                @yield('content')
                <!-- / Main content block -->

            </div>
        </div>

        <!-- Footer block -->
        @include('backend.includes.footer')
        <!-- / Footer block -->

    </div>

    <script type="text/javascript" src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        Object.assign(DataTable.defaults, {
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa-solid fa-file-excel"></i>',
                    titleAttr: 'Excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa-solid fa-file-pdf"></i>',
                    titleAttr: 'Pdf'
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>',
                    titleAttr: 'Stampa'
                }
            ],
            layout: {            
                top: null,
                topStart: 'pageLength',
                topEnd: ['search', 'buttons'],
                bottom: null,
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            language: {
                url: "{{ asset('vendor/DataTables/it-IT.json') }}",
            },
            pageLength: 25
        });

        $( document ).ready(function() {
            window.App.initialize();
        });

    </script>
    @stack('after-scripts')

</body>

</html>
