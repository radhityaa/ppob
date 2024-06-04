<footer class="landing-footer bg-body footer-text">
    <div class="footer-top position-relative overflow-hidden z-1">
        <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg-light.png') }}" alt="footer bg"
            class="footer-bg banner-bg-img z-n1" data-app-light-img="front-pages/backgrounds/footer-bg-light.png"
            data-app-dark-img="front-pages/backgrounds/footer-bg-dark.png" />
        <div class="container">
            <div class="row gx-0 gy-4 g-md-5">
                <div class="col-lg-5">
                    <a href="landing-page.html" class="app-brand-link mb-4">
                        <span class="app-brand-logo demo">
                            <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                    fill="#7367F0" />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                    fill="#161616" />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                    fill="#161616" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                    fill="#7367F0" />
                            </svg>
                        </span>
                        <span class="app-brand-text demo footer-link fw-bold ms-2 ps-1">{{ config('app.name') }}</span>
                    </a>
                    <p class="footer-text footer-logo-description mb-4">
                        AyasyaTech Platform bisnis yang menyediakan Layanan Pulsa, PPOB, dan Topup Game.
                    </p>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-4">Pages</h6>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="/#landingFAQ" class="footer-link">FAQ</a>
                        </li>
                        <li class="mb-3">
                            <a href="/#about" class="footer-link">Tentang Kami</a>
                        </li>
                        <li class="mb-3">
                            <a href="/#landingContact" class="footer-link">Kontak Admin</a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('login') }}" class="footer-link">Login/Register</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-title mb-4">Download app</h6>
                    <a href="javascript:void(0);" class="d-block footer-link mb-3 pb-2"><img
                            src="{{ asset('assets/img/front-pages/landing-page/apple-icon.png') }}"
                            alt="apple icon" /></a>
                    <a href="javascript:void(0);" class="d-block footer-link"><img
                            src="{{ asset('assets/img/front-pages/landing-page/google-play-icon.png') }}"
                            alt="google play icon" /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div
            class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
            <div class="mb-2 mb-md-0">
                <span class="footer-text">©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                </span>
                <a href="#" class="fw-medium text-white footer-link">AyasyaTech
            </div>
            <div>
                <a href="#" class="footer-link me-3" target="_blank">
                    <img src="{{ asset('assets/img/front-pages/icons/github-light.png') }}" alt="github icon"
                        data-app-light-img="front-pages/icons/github-light.png"
                        data-app-dark-img="front-pages/icons/github-dark.png" />
                </a>
                <a href="#" class="footer-link me-3" target="_blank">
                    <img src="{{ asset('assets/img/front-pages/icons/facebook-light.png') }}" alt="facebook icon"
                        data-app-light-img="front-pages/icons/facebook-light.png"
                        data-app-dark-img="front-pages/icons/facebook-dark.png" />
                </a>
                <a href="#" class="footer-link me-3" target="_blank">
                    <img src="{{ asset('assets/img/front-pages/icons/twitter-light.png') }}" alt="twitter icon"
                        data-app-light-img="front-pages/icons/twitter-light.png"
                        data-app-dark-img="front-pages/icons/twitter-dark.png" />
                </a>
                <a href="#" class="footer-link" target="_blank">
                    <img src="{{ asset('assets/img/front-pages/icons/instagram-light.png') }}" alt="google icon"
                        data-app-light-img="front-pages/icons/instagram-light.png"
                        data-app-dark-img="front-pages/icons/instagram-dark.png" />
                </a>
            </div>
        </div>
    </div>
</footer>
