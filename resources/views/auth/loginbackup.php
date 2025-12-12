<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hệ thống kết quả thanh tra | Đăng nhập</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico')}}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
    <div class="login-page-container">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ URL::to('/') . '/images/logo.png' }}" height="70" style="margin-left: 70px;">
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">ĐĂNG NHẬP</p>

                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="has-feedback form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <input id="username" type="text" class="form-control" placeholder="Tên đăng nhập" name="username" value="{{ old('username') }}" required autofocus>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Nhớ tài khoản
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-block btn-flat" style="background: #00a65a;color: rgb(255, 255, 255);margin-top: 4px;border-radius: 4px;">Đăng nhập</button>
                        </div>
                        <div class="col-xs-12">
                            <button class="btn btn-block btn-flat" onclick="window.location.href='login/redirect/oauth'" style="background: #00a65a;color: rgb(255, 255, 255);margin-top: 4px;border-radius: 4px;">Đăng nhập bằng tài khoản VEA</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!-- jQuery 3 -->
    <script src="{{ asset('js/min/jquery.min.js') }}"></script>
    <script src="{{ asset('js/min/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/min/icheck.min.js') }}"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });
        });
    </script>
</body>

</html>
