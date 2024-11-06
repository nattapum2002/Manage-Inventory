<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบจัดการคลังสินค้า</title>
</head>
@include('layouts.css')

<body class="hold-transition login-page">
    @if (session('error'))
        <div class="alert alert-danger ">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    <div class="login-box">
        <div class="login-logo">
            {{-- <a href="../../index2.html"><b>Admin</b>LTE</a> --}}
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('Login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="user_id" name="user_id"
                            placeholder="รหัสพนักงาน" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="รหัสผ่าน" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <a href="forgot-password.html">forgot password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    @include('layouts.js')
</body>

</html>
