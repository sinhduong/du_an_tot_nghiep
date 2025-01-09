@extends('layouts.auth')
@section('content')

<form class="login-form" method="POST" action="{{ route('login') }}" >
    @csrf
    <div class="imgcontainer">
        <a href="index.html"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png')}}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <span class="password-field-show">
            <x-text-input id="password" class="block mt-1 w-full"
            type="password"
            name="password"
            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <span data-toggle=".password-field"
                class="fa fa-fw fa-eye field-icon toggle-password"></span>
        </span>
        <label class="label-container">
            Remember me
            <input type="checkbox" name="remember">
            <span class="checkmark"></span>
        </label>
        <span class="psw">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-btn">Forgot password?</a>

            </span>
            @endif
        <div class="login-btns">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        <div class="division-lines">
            <p>or login with</p>
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
            <span class="already-acc">Not a member? <a href="{{ route('register') }}"
                    class="signup-btn">Sign up</a></span>
        </div>
    </div>
</form>
@endsection
