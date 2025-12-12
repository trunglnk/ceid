
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hệ thống kết quả thanh tra</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico')}}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap css -->
    <link href="{{ asset('css/dist/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome css -->
    <link href="{{ asset('css/dist/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/dist/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/dist/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('css/dist/green.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dist/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="skin-green-light sidebar-mini">
<div class="wrapper" id="app">
    @if (!empty($usersystem))
    <tracuu :usersystem="{{$usersystem}}"></tracuu>
    @else
    <tracuu></tracuu>
    @endif
</div>
<!-- ./wrapper -->
<script src="{{ asset('js/min/jquery.min.js') }}"></script>
<script src="{{ asset('js/min/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/min/icheck.min.js') }}"></script>
<script src="{{asset('js/min/select2.full.min.js')}}"></script>
<script src="{{asset('js/min/base.min.js')}}"></script>
<!-- Mapbox GL -->
<link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.35.1/mapbox-gl.css" rel='stylesheet' />
<script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.35.1/mapbox-gl.js"></script>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
