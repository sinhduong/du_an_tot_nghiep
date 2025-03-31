<section class="py-5" id="rooms">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark position-relative d-inline-block pb-2">
                Mã Giảm Giá
                <span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary" style="width: 50px; height: 3px;"></span>
            </h2>
        </div>

        <div class="row g-4">
            @forelse($promotions as $promotion)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden transition-all hover-shadow">
                        <div class="row g-0">
                            <div class="col-4 text-white d-flex align-items-center justify-content-center discount-bg rounded-start">
                                <div class="text-center p-3">
                                    <h4 class="fw-bold mb-1 animate-number">
                                        @if($promotion->type == 'percent')
                                            {{ $promotion->value }}%
                                        @else
                                            {{ number_format($promotion->value, 0, ',', '.') }}<span class="fs-6"> VNĐ</span>
                                        @endif
                                    </h4>
                                    <small class="text-light opacity-75">Giảm giá</small>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold text-danger mb-3 code-text">{{ $promotion->code }}</h5>
                                    <ul class="list-unstyled text-muted small">
                                        <li class="mb-2">
                                            <strong class="text-dark">Đơn tối thiểu:</strong>
                                            {{ number_format($promotion->min_booking_amount, 0, ',', '.') }} VNĐ
                                        </li>
                                        <li class="mb-2">
                                            <strong class="text-dark">Giảm tối đa:</strong>
                                            {{ number_format($promotion->max_discount_value, 0, ',', '.') }} VNĐ
                                        </li>
                                        <li class="mb-3">
                                            <strong class="text-dark">Hết hạn:</strong>
                                            {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y H:i') : 'Không xác định' }}
                                        </li>
                                    </ul>
                                    <button class="btn btn-primary btn-sm w-100 copy-btn transition-all"
                                            onclick="copyCode('{{ $promotion->code }}')"
                                            data-bs-toggle="tooltip"
                                            title="Sao chép mã">
                                        <i class="fas fa-copy me-2"></i>Sao chép
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Hiện tại chưa có mã giảm giá nào.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
    .discount-bg {
        background: linear-gradient(135deg, #ed5b31 0%, #ff784f 100%);
        transition: all 0.3s ease;
    }

    .card:hover .discount-bg {
        transform: scale(1.05);
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .copy-btn {
        background-color: #ed5b31;
        border: none;
        border-radius: 25px;
        padding: 8px 15px;
    }

    .copy-btn:hover {
        background-color: #ff784f;
        transform: translateY(-2px);
    }

    .code-text {
        letter-spacing: 1px;
        font-family: 'Courier New', Courier, monospace;
    }
</style>

<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã sao chép!',
                text: `Mã ${code} đã được sao chép`,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#fff',
                padding: '1rem',
            });
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể sao chép mã',
                timer: 1500,
                showConfirmButton: false
            });
            console.error('Lỗi khi sao chép: ', err);
        });
    }

    // Khởi tạo tooltip
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
