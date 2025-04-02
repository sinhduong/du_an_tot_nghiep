{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
@extends('layouts.client')

@section('content')
<div class="section-blog padding-tb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @include('profile.partials.update-profile-information-form')
                {{-- @include('profile.partials.delete-user-form') --}}
            </div>
            <div class="col-lg-4 blog-rs">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>
</div>
@endsection
