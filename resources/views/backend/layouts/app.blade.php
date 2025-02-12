<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ language_direction() }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
    <!-- Aggiunta icona per dispositivi mobile -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/apple-touch-icon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <script src="{{ asset('vendor/jquery/jquery-3.6.4.min.js') }}"></script>

    @vite(['resources/sass/app-backend.scss', 'resources/js/app-backend.js'])

    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
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
    <script type="text/javascript" src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
    <script>

        function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
        }

        Object.assign(DataTable.defaults, {
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa-solid fa-file-excel"></i>',
                    titleAttr: 'Excel',
                    action: newexportaction
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa-solid fa-file-pdf"></i>',
                    titleAttr: 'Pdf',
                    action: newexportaction
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>',
                    titleAttr: 'Stampa',
                    action: newexportaction
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
            pageLength: 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Tutti"]],
        });

        $( document ).ready(function() {
            window.App.initialize();
        });

    </script>
    @stack('after-scripts')

</body>

</html>
