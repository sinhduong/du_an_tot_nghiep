@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Đánh giá của khách hàng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>Đánh giá</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="reviews-section padding-tb-100">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            @forelse ($reviews as $review)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="card-title mb-0">{{ $review->user->name }}</h5>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            
                            <p class="card-text">{{ $review->comment }}</p>

                            @if ($review->response)
                                <div class="response-section mt-3 p-3 bg-light rounded">
                                    <h6 class="mb-2">
                                        <i class="fas fa-reply text-primary me-2"></i>
                                        Phản hồi từ khách sạn
                                    </h6>
                                    <p class="mb-0">{{ $review->response }}</p>
                                    <small class="text-muted">
                                        {{ $review->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            @endif

                            <div class="booking-info mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-hotel me-1"></i>
                                    Đặt phòng: {{ $review->booking->booking_code }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chưa có đánh giá nào.
                    </div>
                </div>
            @endforelse
        </div>

        @if($reviews->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</section>

<style>
.rating {
    font-size: 1.2rem;
}

.response-section {
    border-left: 3px solid #007bff;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection 