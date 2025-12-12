<!DOCTYPE html>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('css/dist/all.css') }}">
  @yield('css')
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="skin-green-light sidebar-mini">
  <div class="wrapper" id="app">
    <!-- Main Header -->
    @include('layouts.header')
    <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        @yield('title')
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    @include('layouts.alert')
    @include('layouts.footer')
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="{{ asset('js/min/jquery.min.js') }}"></script>
  <script src="{{ asset('js/min/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/min/icheck.min.js') }}"></script>
  <script src="{{asset('js/min/select2.full.min.js')}}"></script>
  <script src="{{asset('js/min/base.min.js')}}"></script>
  @yield('script')
</body>
</html>
