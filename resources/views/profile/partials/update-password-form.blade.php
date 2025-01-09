<div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
    <div class="row">
        <div class="col-lg-12 rs-pb-24">
            <div class="lh-contact-touch-inner">
                <div class="lh-contact-touch-contain">
                    <h4 class="lh-contact-touch-contain-heading text-center">Đổi mật khẩu</h4>

                </div>
                <div class="lh-contact-touch-inner-form">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="lh-contact-touch-inner-form-warp">
                           <input id="update_password_current_password"  for="update_password_current_password" type="password" name="current_password"  placeholder="Mật khẩu cũ" class="lh-form-control" autocomplete="current-password">

                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                        <div class="lh-contact-touch-inner-form-warp">
                            <input id="update_password_password"
                                   type="password"
                                   name="password"
                                   placeholder="Mật khẩu mới"
                                   class="lh-form-control"
                                   autocomplete="new-password"
                                   value="{{ old('password') }}">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <div class="lh-contact-touch-inner-form-warp">
                            <input id="update_password_password_confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   placeholder="Nhập lại mật khẩu mới"
                                   class="lh-form-control"
                                   autocomplete="new-password"
                                   value="{{ old('password_confirmation') }}">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
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
