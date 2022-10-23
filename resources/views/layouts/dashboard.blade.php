<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/mains.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/cropperjs/cropper.css') }}">
    @stack('css')

    <style>
        .user-panel .info {
            line-height: 1.1 !important;
        }

        .form-check-label.checks {
            cursor: pointer !important;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper">

        @include('layouts.sub.topbar')

        @include('layouts.sub.sidebar')

        <div class="content-wrapper">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('title')</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                @yield('content')
            </section>

        </div>

        @include('layouts.sub.footer')
    </div>

    <script src="{{ asset('assets/dashboard/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/cropperjs/cropper.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/marked.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/adminlte.min.js') }}"></script>
    @stack('js')

    <script>
        $('[data-dismiss="modal"]').on('click', function() {
            $(document).find('span.error-text').text('');
            $(document).find('.form-control').removeClass(
                'is-invalid');
            $(document).find('textarea').removeClass(
                'is-invalid');
            $('form')[0].reset();
        });
    </script>
</body>

</html>
