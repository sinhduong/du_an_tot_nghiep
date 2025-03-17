@extends('layouts.auth')
@section('content')

<form class="signup-form" method="post" action="{{ route('register') }}">
    @csrf
    <div class="imgcontainer">
        <a href=""><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        <div class="row p-l-5 p-r-5">
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="text" placeholder="Nhập tên người dùng" name="name" value="{{old('name')}}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="email" placeholder="Nhập Email" name="email" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            {{-- password --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <div class="password-container" style="position: relative;">
                    <input type="password"
                           id="password"
                           placeholder="Nhập mật khẩu"
                           name="password"
                           class="input-checkmark"
                           required
                           autocomplete="new-password">
                    <span class="toggle-password"
                          onclick="togglePassword('password', 'eye-icon-password')"
                          style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye" id="eye-icon-password"></i>
                    </span>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>
            {{-- confirm password --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <div class="password-container" style="position: relative;">
                    <input class="input-checkmark"
                           id="password_confirmation"
                           type="password"
                           placeholder="Xác nhận mật khẩu"
                           name="password_confirmation"
                           required
                           autocomplete="new-password">
                    <span class="toggle-password"
                          onclick="togglePassword('password_confirmation', 'eye-icon-confirm')"
                          style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye" id="eye-icon-confirm"></i>
                    </span>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
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
@endsection
