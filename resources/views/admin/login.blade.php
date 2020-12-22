<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>登录管理系统 -  Powered by</title>
    <meta name="generator" content="v2.5">
    <meta name="author" content="Team and UI Team">
    <link href="{{asset('css/bootstrap.min-3.4.0.css')}}" rel="stylesheet">
    <link href="{{asset('css/layui.css')}}" rel="stylesheet">
    <link href="{{asset('css/font-awesome.min-4.3.0.css')}}" rel="stylesheet">
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.min-3.0.0.css')}}" rel="stylesheet">
    <script>
        top != window && (top.location.href = location.href);
    </script>
</head>
<body class="gray-bg login-bg">
<div style="width: 100%;position: fixed;background-color: #fff;z-index: 999;">
    <img src="{{asset('images/header.png')}}" alt="">
</div>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div class="login-group">
        <div style="display: flex;flex-wrap: wrap;">
            <button type="button" class="btn btn-default" style="background-color: #fff; border: 1px solid #fff; color: #000;height: 40px;margin-top: 12%;">
                <span class="glyphicon glyphicon-user"></span>
            </button>
            <h3 class="login-logo">
                账号密码登录
            </h3>
        </div>

        <form role="form" action="{{route('admin.login')}}" method="post" id="form">
            @csrf
            <div class="form-group" style="margin-bottom:10px">
                <div class="input-group m-b">
                    <input style="height: 40px; width:370px; border: 1px solid rgb(209, 209, 209);" type="text" id="account" name="email" value="{{old('email')}}" placeholder="邮箱" class="form-control" required autofocus>
                    @error('email')
                        <div style="font-size: 15px;color: #f00;margin-top: 2%;">{{$message}}</div>
                    @enderror

                </div>

            </div>
            <div class="form-group" style="margin-bottom:10px">
                <div class="input-group m-b">
                    <input style="height: 40px; width:370px; border: 1px solid rgb(209, 209, 209);" type="password" class="form-control" id="pwd" name="password" value="{{old('password')}}" placeholder="密码" required autofocus>
                    @error('password')
                        <div style="font-size: 15px;color: #f00;margin-top: 2%;">{{$message}}</div>
                    @enderror
                </div>

            </div>
            <!-- <div class="form-group" style="margin-bottom:60px">
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-shield"></i> </span>
                    <input type="text" style="height: 40px; width:200px;" class="form-control" id="verify" name="verify" placeholder="验证码" required="">
                    <div style="font-size: 15px;color: #f00;margin-top: 2%;">验证码错误</div>
                    <span class="input-group-btn" style="padding: 0;margin: 0;">
                        <img id="verify_img" src="static/picture/captcha.jpg" alt="验证码" style="padding: 0;height: 34px;margin: 0;">
                    </span>
                </div>
            </div> -->
            <button type="submit" style="width: 300px; background-color: rgb(255, 134, 39,1);" class="btn btn-primary block full-width m-b">登 录</button>
        </form>
    </div>
</div>
<div class="footer" style=" position: fixed;bottom: 0;width: 100%;left: 0;margin: 0;opacity: 0.8;">
    <div class="pull-right">© 2017-2020
    </div>
</div>
</body>
</html>
