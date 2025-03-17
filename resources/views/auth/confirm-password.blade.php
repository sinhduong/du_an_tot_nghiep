@extends('layouts.auth')
@section('content')

<form class="forgot-form" method="post" action="{{ route('password.confirm') }}">
    @csrf
    <div class="imgcontainer">
        <a href="index.html"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo" class="logo"></a>
    </div>
    <div class="input-control">

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <span class="password-field-show">
                <input class="password-field input-checkmark"
                    id="password"
                    type="password" placeholder="confirm Password"
                    name="password" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </span>
        <div class="login-btns">
            <button type="submit">Xác nhận</button>
        </div>
    </div>
</form>
@endsection
