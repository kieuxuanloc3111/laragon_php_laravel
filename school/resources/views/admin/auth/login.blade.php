<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đăng nhập giáo viên
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

    <div class="auth-card">

        <div class="auth-header">

            <div class="logo-circle">

                🎓

            </div>

            <h1>
                Đăng nhập giáo viên
            </h1>

            <p>
                Hệ thống quản lý luyện thi THPT
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
            action="/admin/login"
            method="POST"
        >

            @csrf

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
                {{-- REMEMBER ME --}}

                <div class="remember-group">

                    <label class="remember-label">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>
                            Ghi nhớ đăng nhập
                        </span>

                    </label>

                </div>

            </div>

            <button
                type="submit"
                class="btn-submit"
            >

                Đăng nhập

            </button>

        </form>

        <div class="auth-footer">

            Chưa có tài khoản?

            <a href="/admin/register">

                Đăng ký ngay

            </a>

        </div>

    </div>

</div>

</body>

</html>