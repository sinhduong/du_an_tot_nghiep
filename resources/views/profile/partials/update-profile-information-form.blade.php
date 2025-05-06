<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

    <div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
        <div class="row">
            <div class="col-lg-12 rs-pb-24">
                <div class="lh-contact-touch-inner">
                    <div class="avatar-section" data-aos="fade-up" data-aos-duration="1200">
                        <div class="avatar-container">
                            <div class="avatar-wrapper">
                                <img id="avatar-preview"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/client/assets/img/avatar/defau-avatar.jpg') }}"
                                    alt="Avatar" class="avatar-image">
                            </div>
                        </div>

                        <div class="avatar-upload">
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                onchange="previewAvatar(event)">
                        </div>
                    </div>

                    
                    <div class="lh-contact-touch-contain mt-5">
                        <h4 class="lh-contact-touch-contain-heading text-center">Đổi thông tin</h4>
                    </div>

                    <div class="lh-contact-touch-inner-form">
                        @csrf
                        @method('PATCH')

                        <!-- Họ tên -->
                        <div class="lh-contact-touch-inner-form-warp custom-form-group">
                            <input type="text" id="name" name="name" placeholder="Họ và tên"
                                class="lh-form-control custom-form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email (Chỉ đọc) -->
                        <div class="lh-contact-touch-inner-form-warp custom-form-group">
                            <input type="email" id="email" name="email" placeholder="Email"
                                class="lh-form-control custom-form-control" value="{{ old('email', $user->email) }}" readonly>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="lh-contact-touch-inner-form-warp custom-form-group">
                            <input type="text" id="phone" name="phone" placeholder="Số điện thoại"
                                class="lh-form-control custom-form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <!-- Địa chỉ -->
                        <div class="lh-contact-touch-inner-form-warp custom-form-group">
                            <input type="text" id="address" name="address" placeholder="Địa chỉ"
                                class="lh-form-control custom-form-control" value="{{ old('address', $user->address) }}">
                        </div>

                        <!-- CMND/CCCD -->
                        <div class="lh-contact-touch-inner-form-warp custom-form-group">
                            <input type="text" id="id_number" name="id_number" placeholder="CMND/CCCD"
                                class="lh-form-control custom-form-control" value="{{ old('id_number', $user->id_number) }}">
                        </div>

                        <!-- Ảnh căn cước -->
                        <div class="lh-contact-touch-inner-form-warp id-photo-section">
                            <div class="id-photo-container">
                                @if ($user->id_photo)
                                    <img id="id-photo-preview" src="{{ asset('storage/' . $user->id_photo) }}"
                                        alt="ID Photo" class="id-photo-image">
                                @else
                                    <img id="id-photo-preview" src="{{ asset('assets/client/assets/img/avatar/defau-avatar.jpg') }}" 
                                        alt="ID Photo" class="id-photo-image">
                                @endif
                            </div>
                            <div class="id-photo-upload">
                                <input type="file" id="id_photo" name="id_photo" class="lh-form-control"
                                    onchange="previewIdPhoto(event)">
                            </div>
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
                        <div class="lh-contact-touch-inner-form-warp gender-section">
                            <select name="gender" class="lh-form-control gender-select">
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

<style>
.avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    border: 1px dashed #dee2e6;
}

.avatar-container {
    position: relative;
    width: 150px;
    height: 150px;
}

.avatar-wrapper {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.avatar-image:hover {
    transform: scale(1.05);
}

.avatar-upload {
    position: relative;
    width: 100%;
    max-width: 200px;
}

.upload-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 20px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-label:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.upload-icon {
    font-size: 18px;
}

.upload-text {
    font-size: 14px;
    color: #495057;
}

.upload-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.default-avatar {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
    padding: 20px;
}

.user-icon {
    width: 100%;
    height: 100%;
    color: #adb5bd;
}

.avatar-wrapper:hover .user-icon {
    color: #6c757d;
    transform: scale(1.05);
}

.custom-form-group {
    margin-bottom: 20px;
}

.custom-form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.custom-form-control {
    transition: all 0.3s ease;
}

.custom-form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.custom-form-control:read-only {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.custom-form-control:hover:not(:read-only) {
    border-color: #adb5bd;
}

.lh-contact-touch-inner-form-warp {
    margin-bottom: 20px;
}/

.lh-form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.lh-form-control:hover:not(:read-only) {
    border-color: #adb5bd;
}

.id-photo-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    border: 1px dashed #dee2e6;
}

.id-photo-container {
    width: 200px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.id-photo-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.id-photo-image:hover {
    transform: scale(1.05);
}

.id-photo-upload {
    width: 100%;
    max-width: 200px;
}
.lh-form-control {
    padding: 0 20px !important;
}
.id-photo-upload .lh-form-control {
    padding: 8px 12px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.id-photo-upload .lh-form-control:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

.gender-section {
    position: relative;
    background-color: #fff;
    padding: 12px 20px;
    border-radius: 15px;
}
</style>
