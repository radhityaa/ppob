@extends('layouts.welcome.app')

@section('content')
    <!-- Hero: Start -->
    <section id="hero-animation">
        <div id="landingHero" class="section-py landing-hero position-relative">
            <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                class="position-absolute start-50 translate-middle-x object-fit-contain w-100 h-100 top-0" data-speed="1" />
            <div class="container">
                <div class="hero-text-box text-center">
                    <h1 class="text-primary hero-title display-6 fw-bold">{{ $hero->title }}
                    </h1>
                    <h2 class="hero-sub-title h6 mb-4 pb-1">
                        {{ $hero->description }}
                    </h2>
                    <div class="landing-hero-btn d-inline-block position-relative">
                        <span class="hero-btn-item position-absolute d-none d-md-flex text-heading">{{ $hero->small_text }}
                            <img src="{{ asset('assets/img/front-pages/icons/Join-community-arrow.png') }}"
                                alt="Join community arrow" class="scaleX-n1-rtl" /></span>
                        <a href="#landingPricing" class="btn btn-primary btn-lg">{{ $hero->button_text }}</a>
                    </div>
                </div>
                <div id="heroDashboardAnimation" class="hero-animation-img">
                    <a href="#">
                        <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                            <img src="{{ asset('assets/img/front-pages/landing-page/' . $hero->image_hero_dashboard) }}"
                                alt="hero dashboard" class="animation-img"
                                data-app-light-img="{{ 'front-pages/landing-page/' . $hero->image_hero_dashboard }}"
                                data-app-dark-img="{{ 'front-pages/landing-page/' . $hero->image_hero_dashboard_dark }}" />
                            <img src="{{ asset('assets/img/front-pages/landing-page/' . $hero->image_hero_element) }}"
                                alt="hero elements" class="position-absolute hero-elements-img animation-img start-0 top-0"
                                data-app-light-img="{{ 'front-pages/landing-page/' . $hero->image_hero_element }}"
                                data-app-dark-img="{{ 'front-pages/landing-page/' . $hero->image_hero_element_dark }}" />
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="landing-hero-blank"></div>
    </section>

    <!-- Hero: End -->

    <!-- Useful features: Start -->
    <x-welcome-about />
    <!-- Useful features: End -->

    <!-- Real customers reviews: Start -->
    <x-welcome-review />
    <!-- Real customers reviews: End -->

    <!-- Our great team: Start -->
    <x-welcome-team />
    <!-- Our great team: End -->

    <!-- Pricing plans: Start -->
    <x-welcome-pricing />
    <!-- Pricing plans: End -->

    <!-- Fun facts: Start -->
    <x-welcome-statistic />
    <!-- Fun facts: End -->

    <!-- FAQ: Start -->
    <x-welcome-faq />
    <!-- FAQ: End -->

    <!-- CTA: Start -->
    <section id="landingCTA" class="section-py landing-cta position-relative p-lg-0 pb-0">
        <img src="{{ asset('assets/img/front-pages/backgrounds/cta-bg-light.png') }}"
            class="position-absolute scaleX-n1-rtl h-100 w-100 z-n1 bottom-0 end-0" alt="cta image"
            data-app-light-img="front-pages/backgrounds/cta-bg-light.png"
            data-app-dark-img="front-pages/backgrounds/cta-bg-dark.png" />
        <div class="container">
            <div class="row align-items-center gy-5 gy-lg-0">
                <div class="col-lg-6 text-lg-start text-center">
                    <h6 class="h2 text-primary fw-bold mb-1">Siap Memulai?</h6>
                    <p class="fw-medium mb-4">Mulai dengan akun Gratis</p>
                    <a href="{{ route('register') }}" class="btn btn-lg btn-primary">Daftar</a>
                </div>
                <div class="col-lg-6 pt-lg-5 text-lg-end text-center">
                    <img src="{{ asset('assets/img/front-pages/landing-page/cta-dashboard.png') }}" alt="cta dashboard"
                        class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
    <!-- CTA: End -->

    <!-- Contact Us: Start -->
    <section id="landingContact" class="section-py bg-body landing-contact">
        <div class="container">
            <div class="mb-3 pb-1 text-center">
                <span class="badge bg-label-primary">Contact US</span>
            </div>
            <h3 class="mb-1 text-center">
                <span class="position-relative fw-bold z-1">Let's work
                    <img src="../../assets/img/front-pages/icons/section-title-icon.png" alt="laptop charging"
                        class="section-title-img position-absolute object-fit-contain z-n1 bottom-0" />
                </span>
                together
            </h3>
            <p class="mb-lg-5 pb-md-3 mb-4 text-center">Ada pertanyaan atau komentar? cukup tulis pesan kepada kami</p>
            <div class="row gy-4">
                <div class="col-lg-5">
                    <div class="contact-img-box position-relative h-100 border p-2">
                        <img src="../../assets/img/front-pages/icons/contact-border.png" alt="contact border"
                            class="contact-border-img position-absolute d-none d-md-block scaleX-n1-rtl" />
                        <img src="../../assets/img/front-pages/landing-page/contact-customer-service.png"
                            alt="contact customer service" class="contact-img w-100 scaleX-n1-rtl" />
                        <div class="px-4 pb-1 pt-3">
                            <div class="row gy-3 gx-md-4">
                                <div class="col-md-6 col-lg-12 col-xl-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-label-primary me-2 rounded p-2"><i
                                                class="ti ti-mail ti-sm"></i></div>
                                        <div>
                                            <p class="mb-0">Email</p>
                                            <h5 class="mb-0">
                                                <a href="mailto:example@gmail.com"
                                                    class="text-heading">example@gmail.com</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12 col-xl-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-label-success me-2 rounded p-2">
                                            <i class="ti ti-phone-call ti-sm"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Phone</p>
                                            <h5 class="mb-0"><a href="tel:+1234-568-963" class="text-heading">+1234 568
                                                    963</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-1">Kirim Pesan</h4>
                            <p class="mb-4">
                                Jika Anda ingin mendiskusikan apa pun yang berkaitan dengan pembayaran, akun, perizinan, <br
                                    class="d-none d-lg-block" />
                                kemitraan, atau memiliki pertanyaan penjualan, Silahkan isi formulir dibawah ini.
                            </p>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="contact-form-fullname">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="contact-form-fullname"
                                            placeholder="john" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="contact-form-email">Email</label>
                                        <input type="text" id="contact-form-email" class="form-control"
                                            placeholder="johndoe@gmail.com" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="contact-form-message">Pesan</label>
                                        <textarea id="contact-form-message" class="form-control" rows="8" placeholder="Tuliskan sesuatu"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Us: End -->
@endsection
