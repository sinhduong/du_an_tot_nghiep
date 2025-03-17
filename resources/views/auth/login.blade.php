@extends('layouts.auth')
@section('content')

<form class="login-form" method="POST" action="{{ route('login') }}">
    @csrf
    <div class="imgcontainer">
        <a href="index.html"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png')}}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <div class="password-container" style="position: relative;">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full password-field"
                type="password"
                name="password"
                required
                autocomplete="current-password" />
            <span class="toggle-password"
                  onclick="togglePassword()"
                  style="position: absolute; right: 10px; top: 50%; transforme: translateY(-50%); cursor: pointer;">
                <i class="fa fa-eye" id="eye-icon"></i>
            </span>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label class="label-container">
            Ghi nhớ tôi
            <input type="checkbox" name="remember">
            <span class="checkmark"></span>
        </label>
        <span class="psw">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-btn">Quên mật khẩu?</a>
            </span>
            @endif
        <div class="login-btns">
            <x-primary-button class="ms-3">
                {{ __('Đăng nhập') }}
            </x-primary-button>
        </div>
        <div class="division-lines">
            <p>hoặc đăng nhập bằng</p>
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
            <span class="already-acc">Chưa phải thành viên? <a href="{{ route('register') }}"
                    class="signup-btn">Đăng ký</a></span>
        </div>
    </div>
</form>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

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
