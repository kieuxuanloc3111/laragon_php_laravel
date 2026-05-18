<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */

        .sidebar {
            width: 240px;
            background: #1f2937;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 0;
        }

        .sidebar a:hover {
            color: #60a5fa;
        }

        /* MAIN */

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */

        .header {
            background: white;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        /* CONTENT */

        .content {
            flex: 1;
            padding: 20px;
        }

        /* FOOTER */

        .footer {
            background: white;
            padding: 15px 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="layout">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="main">

        {{-- Header --}}
        @include('admin.partials.header')

        {{-- Page Content --}}
        <div class="content">
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('admin.partials.footer')

    </div>

</div>

</body>
</html>