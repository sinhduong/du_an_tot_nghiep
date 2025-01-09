

    <div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
        <div class="row">
            <div class="col-lg-12 rs-pb-24">
                <div class="lh-contact-touch-inner">
                    <div class="lh-our-blog aos-init aos-animate" data-aos="fade-up" data-aos-duration="1200">
                        <div class="lh-businessman-detalis">
                            <div class="lh-businessman-detalis-image">
                             <img src="{{ asset('assets/client/assets/img/blog/busness-1.jpg') }}" alt="businessman">
                            </div>
                        </div>
                    </div>
                    <div class="lh-contact-touch-contain">
                        <h4 class="lh-contact-touch-contain-heading text-center">Đổi thông tin</h4>

                    </div>
                    <div class="lh-contact-touch-inner-form">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="lh-contact-touch-inner-form-warp">
                                <input type="text" id="name" name="name" placeholder="name" class="lh-form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div class="lh-contact-touch-inner-form-warp">
                                <input type="email" id="email" name="email" placeholder="email" class="lh-form-control" value="{{ old('email',$user->email) }}" required autocomplete="username">
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                  Your email address is unverified
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                      Click here to re-send the verification email
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                      A new verification link has been sent to your email address
                                    </p>
                                @endif
                            </div>
                        @endif
                            </div>
                            <div class="flex items-center gap-4">
                                <button class="lh-buttons result-placeholder" type="submit">
                                    Cập nhật
                                </button>

                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


