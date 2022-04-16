<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="{{ coach_asset('libs/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ coach_asset('libs/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    <script src="{{ coach_asset('libs/popper.min.js') }}"></script>
    <script src="{{ coach_asset('libs/bootstrap-5.0.2-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ coach_asset('libs/bootstrap-toggle-2.2.2/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ coach_asset('libs/select2/select2.full.min.js') }}"></script>
    <script src="{{ coach_asset('libs/toastr/toastr.min.js') }}"></script>
{{--    <script src="{{ coach_asset('libs/fabric/fabric-4.5.0.min.js') }}"></script>--}}
    <script src="{{ coach_asset('js/coach.js') }}"></script>
    <script src="{{ coach_asset('js/socket.js') }}"></script>


    <link rel="stylesheet" href="{{ coach_asset('libs/jquery-ui-1.12.1/jquery-ui.min.css') }}"/>
    <link rel="stylesheet" href="{{ coach_asset('libs/bootstrap-toggle-2.2.2/bootstrap-toggle.min.css') }}"/>
    <link rel="stylesheet" href="{{ coach_asset('libs/bootstrap-5.0.2-dist/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ coach_asset('libs/fontawesome-free-5.15.4-web/css/all.css') }}">
    <link rel="stylesheet" href="{{ coach_asset('libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ coach_asset('libs/toastr/toastr.min.css') }}"/>
    <link rel="stylesheet" href="{{ coach_asset('css/coach.css') }}"/>
    <title>
        @yield('title')
    </title>

        @yield('css')


    @yield('js')

    @yield('meta_head')

</head>
<body>
<div class="content">
    <div class="page-content browse container-fluid">
        <div class="alerts"></div>
        @yield('buttons')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @yield('contents')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

