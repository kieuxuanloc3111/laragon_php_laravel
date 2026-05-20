<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đăng ký giáo viên
    </title>

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/auth.css') }}">

</head>

<body>

<div class="auth-wrapper">

    <div class="auth-card register-card">

        <div class="auth-header">

            <div class="logo-circle">

                🎓

            </div>

            <h1>
                Đăng ký giáo viên
            </h1>

            <p>
                Tạo tài khoản để quản lý đề thi
            </p>

        </div>

        @if($errors->any())

            <div class="alert-error">

                @foreach($errors->all() as $error)

                    <p>
                        {{ $error }}
                    </p>

                @endforeach

            </div>

        @endif

        <form
            action="/admin/register"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            {{-- NAME --}}

            <div class="form-group">

                <label>
                    Họ và tên
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ví dụ: Nguyễn Văn A"
                >

            </div>

            {{-- EMAIL --}}

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Nhập email..."
                >

            </div>

            {{-- PASSWORD --}}

            <div class="form-group">

                <label>
                    Mật khẩu
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Nhập mật khẩu..."
                >

            </div>

            {{-- CONFIRM PASSWORD --}}

            <div class="form-group">

                <label>
                    Xác nhận mật khẩu
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Nhập lại mật khẩu..."
                >

            </div>

            {{-- AVATAR --}}

            <div class="form-group">

                <label>
                    Ảnh đại diện
                </label>

                <input
                    type="file"
                    name="image"
                    class="file-input"
                >

            </div>

            <button
                type="submit"
                class="btn-submit"
            >

                Đăng ký tài khoản

            </button>

        </form>

        <div class="auth-footer">

            Đã có tài khoản?

            <a href="/admin/login">

                Đăng nhập

            </a>

        </div>

    </div>

</div>

</body>

</html>