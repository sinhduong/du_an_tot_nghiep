@extends('layouts.auth')
@section('content')

<form class="forgot-form" method="post" action="{{ route('password.email') }}">
    @csrf
    <div class="imgcontainer">
        <a href="index.html"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        <p>Nhập email của bạn, chúng tôi sẽ gửi liên kết để đặt lại mật khẩu của bạn.</p>
        <input type="email" id="email" placeholder="Enter your email" name="email" required value="{{ old('email') }}">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <div class="login-btns">
            <button type="submit">Reset</button>
        </div>
        <div class="login-with-btns">
            <span class="already-acc">Return to<a href="{{ route('login') }}" class="login-btn">Login</a></span>
        </div>
    </div>
</form>
@endsection