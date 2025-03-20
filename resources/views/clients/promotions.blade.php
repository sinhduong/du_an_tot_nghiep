<section class="py-2" id="rooms">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">Mã giảm giá</h2>
        </div>

        <div class="row">
            @forelse($promotions as $promotion)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-1 shadow-sm">
                        <div class="row g-0">
                            <div class="col-4 text-white d-flex align-items-center justify-content-center rounded-start" style="background-color: #ed5b31">
                                <div class="text-center">
                                    <h4 class="fw-bold mb-1">
                                        @if($promotion->type == 'percent')
                                            {{ $promotion->value }}%
                                        @else
                                            {{ number_format($promotion->value, 0, ',', '.') }} VNĐ
                                        @endif
                                    </h4>
                                    <small>Giảm giá</small>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-danger">{{ $promotion->code }}</h5>
                                    <p class="card-text text-muted small mb-1">
                                        <strong>Đơn tối thiểu:</strong> {{ number_format($promotion->min_booking_amount, 0, ',', '.') }} VNĐ
                                    </p>
                                    <p class="card-text text-muted small mb-1">
                                        <strong>Giảm tối đa:</strong> {{ number_format($promotion->max_discount_value, 0, ',', '.') }} VNĐ
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <strong>Hết hạn:</strong>
                                        {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y H:i') : 'Không xác định' }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-outline-primary btn-sm" onclick="copyCode('{{ $promotion->code }}')">
                                            Sao chép mã
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Không có mã giảm giá nào.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Đã sao chép mã: ' + code,
                showConfirmButton: false,
                timer: 1500
            });
        }).catch(err => {
            console.error('Lỗi khi sao chép mã: ', err);
        });
    }
</script>
