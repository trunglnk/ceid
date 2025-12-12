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

<body class="background_login">
    <div class="login-page-container">
        <div>
            <div class="text_login">
                HỆ THỐNG KẾT QUẢ THANH TRA MÔI TRƯỜNG
            </div>
            <div class="background_body">
                <div class="computer"></div>
                <div class="box_login">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="logo_login"></div>
                        <div class="text_box_login"><b>Đăng nhập</b></div>
                        @if (count($errors) >0)
                        <p class="text-danger" style="font-size: 16px; text-align: center"><b>Tên đăng nhập hoặc mật khẩu không chính xác</b> </p>
                        @endif
                        <div class="input_box_login">
                            <div class="hinhanh"></div>
                            <div class="has-feedback form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <input id="username" type="text" class="form-control" style=" border: none; background-color: #FFFFFF; margin-top:14px; " placeholder="Tên đăng nhập" name="username" value="{{ old('username') }}" required autofocus>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="input_box_login">
                            <div class="khoa"></div>
                            <div class="form-group has-feedback">
                                <input type="password" class="form-control" style=" border: none; background-color: #FFFFFF; margin-top:14px; " placeholder="Mật khẩu" id="password" name="password" required>
                                <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <!-- <div class="mat"></div> -->
                        </div>
                        <div class="checkbox icheck"  style="margin-left: 75px; margin-top: 20px">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Nhớ tài khoản
                            </label>
                        </div>
                        <!-- <button type="submit" class="button_box_login"><b>Đăng nhập</b></button> -->
                        <button type="submit" class="btn btn-block btn-flat" style="width: 387px; height: 49px; border-radius: 24.5px; background-color: #043160; margin-top: 24px; margin-left: 60px; display: flex; align-items: center; color: #FFFFFF; justify-content: center;">
                            Đăng nhập
                        </button>
                    </form>
                    <div style="width: 100%;display: flex; justify-content: center; margin-top: 15px">
                        {{-- <div onclick="window.location.href='login/redirect/oauth'" style="color: #00a65a; font-weight: bold; cursor: pointer">Đăng nhập bằng tài khoản VEA</div> --}}
                    </div>
                </div>
            </div>
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
<style>
    .background_login {
        width: 100%;
        background-color: #F5F5F5;
        background-image: url('{{ asset("/images/backgroundLogin.svg")}}');
        background-position: center;
        height: 100%;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .text_login {
        width: 512px;
        height: 36px;
        font-size: 24px;
        padding-top: 176px;
        margin-left: 153px;
        margin-bottom: 50px;
        color: #043160;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .computer {
        width: 609px;
        height: 437.57px;
        margin-top: 70px;
        margin-left: 106px;
        margin-right: 324px;
        background-image: url('{{ asset("/images/computer.svg")}}');
    }

    .box_login {
        width: 507px;
        height: 510px;
        border-radius: 40px;
        background-color: #FFFFFF;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .logo_login {
        width: 136px;
        height: 132px;
        margin-top: 42px;
        margin-left: 186px;
        background-image: url('{{ asset("/images/logo.svg")}}');
    }

    .text_box_login {
        width: 127px;
        height: 29px;
        font-size: 24px;
        margin-left: 190px;
    }

    .input_box_login {
        width: 387px;
        height: 49px;
        border-radius: 24.5px;
        border: 1px solid #CCCCCC;
        margin-top: 24px;
        margin-left: 60px;
        display: flex;
        align-items: center;
    }

    .hinhanh {
        width: 15px;
        height: 15px;
        margin-right: 11px;
        margin-left: 15px;
        background-image: url('{{ asset("/images/anh_1.svg")}}');
    }

    .text_input {
        border: none;
        font-size: 14px;
    }

    /* .mat {
        width: 14px;
        height: 10px;
        margin-left: 100px;
        margin-right: 11px;
        background-image: url('{{ asset("/images/mat.svg")}}');
    } */

    .khoa {
        width: 14px;
        height: 19px;
        margin-left: 15px;
        margin-right: 11px;
        background-image: url('{{ asset("/images/khoa.svg")}}');
    }

    .button_box_login {
        width: 387px;
        height: 49px;
        border-radius: 24.5px;
        background-color: #043160;
        margin-top: 24px;
        margin-left: 60px;
        display: flex;
        align-items: center;
        color: #FFFFFF;
        justify-content: center;
    }

    .background_body {
        display: flex;
    }

    @media screen and (max-width:1600px) {
        .background_body {
            display: flex;
            justify-content: space-around;
        }

        .computer {
            margin-left: 0px;
            margin-right: 0px;
        }
    }

    @media screen and (max-width:1200px) {
        .computer {
            width: 0px;
            margin-left: 0px;
            margin-right: 0px;
        }

        .box_login {
            margin: auto;
        }
    }

    @media screen and (max-height:700px) {
        .background_login {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .background_body {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .text_login {
            padding: 20px 0px;
            margin: 0px 153px
        }
    }

    @media screen and (max-height:500px) {
        .background_login {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .background_body {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .text_login {
            padding: -20px 0px;
            margin: 0px 153px
        }

        .computer {
            margin-top: 0px;
        }
    }
</style>