<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบจัดการคลังสินค้า : @yield('title')</title>
</head>
    @include('layouts.css')
<body>
    <div class="content-wrapper">
        <section class="content">
            @yield('content')
        </section>
    </div>
    @include('layouts.js')
</body>
</html>