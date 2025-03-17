@extends('layouts.auth')
@section('content')

<form class="login-form" method="post" action="{{ route('password.store') }}">
    @csrf
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <!-- Email Address -->
    <div class="imgcontainer">
        <a href=""><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">
        {{-- email  --}}
        <input type="text" id="email" placeholder="Nhập Email" name="email" value="{{ old('email', $request->email) }}"
            required>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                {{-- pass  --}}
        <span class="password-field-show">
            <input type="password" id="password" placeholder="Nhập mật khẩu" name="password" autocomplete="new-password"
            class="input-checkmark" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <span class="password-field-show">
                    <input class="password-field input-checkmark"
                        id="password_confirmation"
                        type="password" placeholder="Xác nhận mật khẩu"
                        name="password_confirmation" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </span>
        </span>
        <label class="label-container">Ghi nhớ tôi
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        <div class="login-btns">
            <button type="submit">Đặt lại mật khẩu</button>
        </div>
    </div>
</form>
@endsection
