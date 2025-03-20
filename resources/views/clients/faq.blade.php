@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Câu hỏi thường gặp</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">câu hỏi thường gặp</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="section-room padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="rooms">
        <div class="container">
           

            <div class="accordion" id="faqAccordion">
                @foreach($faqs as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $index }}">
                                {{ $faq->question }} <!-- Trường câu hỏi -->
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}"
                             class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                             aria-labelledby="heading{{ $index }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {{ $faq->answer }} <!-- Trường câu trả lời -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
