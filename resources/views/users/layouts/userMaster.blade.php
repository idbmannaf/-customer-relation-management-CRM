<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="u
tf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ env('APP_NAME') }} @stack('title')</title>
    <link rel="stylesheet" href="{{ asset('back/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('back/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/dist/css/adminlte.min.css') }}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('back/w3.css') }}">
    @stack('css')
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed text-sm control-sidebar-push-slide-">
    <div class="wrapper">
        <!-- Navbar -->
        @include('users.layouts.inc.userHeader')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('users.layouts.inc.userLeftSidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content')
                    <form action="">
                        <div class="user-location-set" data-url="{{ route('user.locationSet') }}"></div>
                        <input type="hidden" id="lati" class="form-control" value="">
                        <input type="hidden" id="long" class="form-control" value="">

                        <input type="hidden" id="output_distance" class="form-control" value="0">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('back/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('back/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('back/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('back/dist/js/adminlte.js') }}"></script>
    @include('locationTrack');
    @stack('js')

</body>

</html>
