@extends('layouts.auth')
@section('content')

<form class="signup-form" method="post" action="{{ route('register') }}">
    @csrf
    <div class="imgcontainer">
        <a href=""><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        <div class="row p-l-5 p-r-5">
            {{-- Name --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="text" placeholder="Nhập tên người dùng" name="name" value="{{ old('name') }}"  >
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            {{-- Email --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="email" placeholder="Nhập Email" name="email" value="{{ old('email') }}"  >
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            {{-- Password --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <div class="password-container" style="position: relative;">
                    <input type="password"
                           id="password"
                           placeholder="Nhập mật khẩu"
                           name="password"

                           autocomplete="new-password">
                    <span class="toggle-password"
                          onclick="togglePassword('password', 'eye-icon-password')"
                          style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye" id="eye-icon-password"></i>
                    </span>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            {{-- Confirm Password --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <div class="password-container" style="position: relative;">
                    <input id="password_confirmation"
                           type="password"
                           placeholder="Xác nhận mật khẩu"
                           name="password_confirmation"

                           autocomplete="new-password">
                    <span class="toggle-password"
                          onclick="togglePassword('password_confirmation', 'eye-icon-confirm')"
                          style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye" id="eye-icon-confirm"></i>
                    </span>
                </div>
                @error('password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <label class="label-container">Tôi đồng ý với <a href="#">chính sách bảo mật</a>
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        <div class="login-btns">
            <button type="submit">Đăng ký</button>
        </div>
        <div class="division-lines">
            <p>hoặc đăng ký bằng</p>
        </div>
        <div class="login-with-btns">
            <button type="button" class="google">
                <i class="ri-google-fill"></i>
            </button>
            <button type="button" class="facebook">
                <i class="ri-facebook-fill"></i>
            </button>
            <button type="button" class="twitter">
                <i class="ri-twitter-fill"></i>
            </button>
            <button type="button" class="linkedin">
                <i class="ri-linkedin-fill"></i>
            </button>
            <span class="already-acc">Bạn đã có tài khoản? <a
                    href="{{ route('login') }}" class="login-btn">Đăng nhập</a></span>
        </div>
    </div>
</form>

<script>
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(iconId);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

<style>
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>

@endsection
