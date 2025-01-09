{{-- <div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
    <div class="row">
        <div class="col-lg-12 rs-pb-24">
            <div class="lh-contact-touch-inner">
                <div class="lh-contact-touch-contain">
                    <h4 class="lh-contact-touch-contain-heading">Sau khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của tài khoản đó sẽ bị xóa vĩnh viễn. Trước khi xóa tài khoản của bạn, vui lòng tải xuống mọi dữ liệu hoặc thông tin mà bạn muốn giữ lại.</h4>

                </div>
                <div class="lh-contact-touch-inner-form">

                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="lh-contact-touch-inner-form-warp">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   placeholder="Mật khẩu"
                                   class="lh-form-control"
                                   autocomplete="new-password"
                                   value="{{ old('password') }}">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <button class="lh-buttons result-placeholder" type="submit">
                            Xóa tài khoản
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="lh-contact-touch aos-init aos-animate mb-2" data-aos="fade-up" data-aos-duration="2000">
    <div class="row">
        <div class="col-lg-12 rs-pb-24">
            <div class="lh-contact-touch-inner">
                <div class="lh-contact-touch-contain">
                    <h4 class="lh-contact-touch-contain-heading">
                        Sau khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của tài khoản đó sẽ bị xóa vĩnh viễn.
                        Trước khi xóa tài khoản của bạn, vui lòng tải xuống mọi dữ liệu hoặc thông tin mà bạn muốn giữ lại.
                    </h4>
                </div>
                <div class="lh-contact-touch-inner-form">
                    <form id="delete-account-form">
                        @csrf
                        <div class="lh-contact-touch-inner-form-warp">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   placeholder="Mật khẩu"
                                   class="lh-form-control"
                                   autocomplete="new-password"
                                   value="">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <button id="delete-account-btn" class="lh-buttons result-placeholder" type="button">
                            Xóa tài khoản
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteButton = document.getElementById("delete-account-btn");

    deleteButton.addEventListener("click", function () {
        // Hiển thị thông báo xác nhận
        if (!confirm("Bạn có chắc chắn muốn xóa tài khoản không?")) {
            return;
        }

        // Lấy giá trị mật khẩu từ input
        const password = document.getElementById("password").value;

        if (!password) {
            alert("Vui lòng nhập mật khẩu.");
            return;
        }

        // Gửi yêu cầu AJAX
        fetch("{{ route('profile.destroy') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({
                _method: "DELETE",
                password: password
            }),
        })
            .then(async (response) => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || "Có lỗi xảy ra!");
                }
                alert(data.message); // Hiển thị thông báo từ server
                window.location.href = "/"; // Điều hướng nếu thành công
            })
            .catch((error) => {
                alert(error.message); // Hiển thị thông báo lỗi
                console.error(error);
            });
    });
});


</script>
