<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบจัดการคลังสินค้า : @yield('title')</title>
</head>
@include('layouts.css')

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('Dashboard.' . Auth::user()->user_type) }}" class="nav-link">Dashboard</a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="{{ route('Logout') }}" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-none d-md-inline"></span>
                    </a>
                </li>
            </ul>
        </nav>

        @include('layouts.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-1">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @if (!Route::is('Dashboard.' . Auth::user()->user_type))
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('Dashboard.' . Auth::user()->user_type) }}">Dashboard</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                    @if (session('success') || session('error') || session('info'))
                        @php
                            $alertType = session('success')
                                ? 'alert-success'
                                : (session('error')
                                    ? 'alert-danger'
                                    : 'alert-info');
                            $message = session('success') ?? (session('error') ?? session('info'));
                        @endphp

                        <div class="alert {{ $alertType }} alert-dismissible fade show auto-dismiss-alert"
                            role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </section>

            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <strong>Manage Inventory System (MIS)</strong>
        </footer>
    </div>

    @include('layouts.js')
</body>

</html>
