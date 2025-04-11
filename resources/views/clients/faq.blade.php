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

  
    <section class="section-faq padding-tb-100">
        <div class="container">
            <div class="banner" data-aos="fade-up" data-aos-duration="1500">
                <h2>Những câu hỏi <span>thường gặp  </span></h2>
            </div>
            <div class="ld-faq" data-aos="fade-up" data-aos-duration="2000">
                <div class="row">
                <div class="col-lg-6 rs-pb-24">
                    <div class="accordion" id="accordionExample">
                        @foreach ($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button shadow-none {{ $index == 0 ? '' : 'collapsed' }} " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    {{ $faq->question }} <!-- Trường câu hỏi -->
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        {{ $faq->answer }} <!-- Trường câu trả lời -->
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 rs-pb-24">
                    <div class="lh-faq-image">
                        <img src="{{asset('storage/' . $systems->img_faqs)}}" alt="faq-side-image">
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
