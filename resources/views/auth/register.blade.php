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
                <input type="text" placeholder="Enter Username" name="name" value="{{old('name')  }}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="email" placeholder="Enter Email" name="email" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            {{-- passwword --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <input type="password" id="password" placeholder="Enter Password" name="password" autocomplete="new-password"
                    class="input-checkmark" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            {{-- confirm password  --}}
            <div class="col-md-6 p-l-10 p-r-10">
                <span class="password-field-show">
                    <input class="password-field input-checkmark"
                        id="password_confirmation"
                        type="password" placeholder="confirm Password"
                        name="password_confirmation" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </span>
            </div>
        </div>
        <label class="label-container">I agree with <a href="#"> privacy
                policy</a>
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        <div class="login-btns">
            <button type="submit">Sign up</button>
        </div>
        <div class="division-lines">
            <p>or signup with</p>
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
            <span class="already-acc">Already you have an account? <a
                    href="{{ route('login') }}" class="login-btn">Login</a></span>
        </div>
    </div>
</form>
@endsection
