<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'THPT Admin')</title>

    {{-- GOOGLE FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    {{-- SWEET ALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- GLOBAL CSS --}}
    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/app.css') }}">

    {{-- PAGE CSS --}}
    @stack('styles')

</head>

<body>

<div class="admin-layout">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="main-wrapper">

        {{-- Header --}}
        @include('admin.partials.header')

        {{-- Content --}}
        <main class="main-content">

            @yield('content')

        </main>

        {{-- Footer --}}
        @include('admin.partials.footer')

    </div>

</div>

{{-- GLOBAL JS --}}
<script src="{{ asset('assets/admin/js/app.js') }}"></script>
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> -->
{{-- PAGE JS --}}
@stack('scripts')
<script>

window.MathJax = {

    tex: {
        inlineMath: [['\\(','\\)']]
    }

};

</script>

<script
src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
</script>
</body>
</html>