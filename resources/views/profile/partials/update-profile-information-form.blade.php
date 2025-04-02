<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

    <div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
        <div class="row">
            <div class="col-lg-12 rs-pb-24">
                <div class="lh-contact-touch-inner">
                    <div class="lh-our-blog aos-init aos-animate" data-aos="fade-up" data-aos-duration="1200">
                        <div class="lh-businessman-detalis">
                            <div class="lh-businessman-detalis-image">
                                <img id="avatar-preview"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
                                    alt="Avatar" width="150" style="border-radius: 50%;">
                            </div>
                        </div>

                        <div class="lh-contact-touch-inner-form-warp">
                            <label for="avatar">Chọn ảnh đại diện:</label>
                            <input type="file" id="avatar" name="avatar" class="lh-form-control" accept="image/*"
                                onchange="previewAvatar(event)">
                        </div>

                    </div>
                    <div class="lh-contact-touch-contain">
                        <h4 class="lh-contact-touch-contain-heading text-center">Đổi thông tin</h4>
                    </div>
                    <div class="lh-contact-touch-inner-form">
                        @csrf
                        @method('PATCH')

                        <!-- Họ tên -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="text" id="name" name="name" placeholder="Họ và Tên"
                                class="lh-form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email (Chỉ đọc) -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="email" id="email" name="email" placeholder="Email"
                                class="lh-form-control" value="{{ old('email', $user->email) }}" readonly>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="text" id="phone" name="phone" placeholder="Số điện thoại"
                                class="lh-form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <!-- Địa chỉ -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="text" id="address" name="address" placeholder="Địa chỉ"
                                class="lh-form-control" value="{{ old('address', $user->address) }}">
                        </div>

                        <!-- CMND/CCCD -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="text" id="id_number" name="id_number" placeholder="CMND/CCCD"
                                class="lh-form-control" value="{{ old('id_number', $user->id_number) }}">
                        </div>

                        <!-- Ảnh căn cước -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="file" id="id_photo" name="id_photo" class="lh-form-control"
                                onchange="previewIdPhoto(event)">

                            @if ($user->id_photo)
                                <img id="id-photo-preview" src="{{ asset('storage/' . $user->id_photo) }}"
                                    alt="ID Photo" width="100">
                            @else
                                <img id="id-photo-preview" src="{{ asset('default-id-photo.png') }}" alt="ID Photo"
                                    width="100">
                            @endif
                        </div>

                        <!-- Ngày sinh -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="date" id="birth_date" name="birth_date" class="lh-form-control"
                                value="{{ old('birth_date', $user->birth_date ? date('Y-m-d', strtotime($user->birth_date)) : '') }}">

                        </div>

                        <!-- Quốc gia -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <input type="text" id="country" name="country" placeholder="Quốc gia"
                                class="lh-form-control" value="{{ old('country', $user->country) }}">
                        </div>

                        <!-- Giới tính -->
                        <div class="lh-contact-touch-inner-form-warp">
                            <select name="gender" class="lh-form-control">
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="lh-buttons result-placeholder" type="submit">Cập nhật</button>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600">Thông tin đã được cập nhật!</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function previewIdPhoto(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('id-photo-preview');
            if (output) {
                output.src = reader.result;
            }
        };
        if (event.target.files.length > 0) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
