<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'THPT Admin')</title>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
</head>
<body>

<div class="admin-layout">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main --}}
    <div class="main-wrapper">

        {{-- Header --}}
        @include('admin.partials.header')

        {{-- Content --}}
        <main class="main-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('admin.partials.footer')
        {{-- PAGE CSS --}}
        @stack('styles')

    </div>

</div>

{{-- Main JS --}}
<script src="{{ asset('assets/admin/js/app.js') }}"></script>

</body>
</html>