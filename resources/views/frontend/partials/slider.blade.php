@push('css')
    <style>
        .hero-section {
            position: relative;
        }

        .hero-slide {
            height: 90vh;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            color: #fff;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
        }

        .hero-content {
            position: relative;
            max-width: 650px;
            z-index: 2;
            animation: fadeUp 1s ease;
        }

        .hero-content h1 {
            font-size: 52px;
            font-weight: 800;
            margin: 15px 0;
        }

        .hero-content p {
            font-size: 18px;
            opacity: 0.9;
        }

        .hero-btns .btn {
            margin-right: 10px;
            padding: 12px 25px;
            border-radius: 50px;
        }

        .badge {
            padding: 8px 15px;
            font-size: 13px;
            border-radius: 20px;
        }

        @keyframes fadeUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Swiper buttons */
        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
        }
    </style>
@endpush
<section class="hero-section">
    <div class="swiper myHeroSwiper">

        <div class="swiper-wrapper">

            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image:url('{{ asset($filePath) }}/images/banner/banner-1.jpg')">

                    <div class="overlay"></div>

                    <div class="container">
                        <div class="hero-content">
                            <span class="badge">🔥 New Collection</span>
                            <h1>Build Your Dream Website</h1>
                            <p>High quality Laravel + Bootstrap UI with modern design system.</p>

                            <div class="hero-btns">
                                <a href="#" class="btn btn-primary btn-lg">Shop Now</a>
                                <a href="#" class="btn btn-outline-light btn-lg">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="hero-slide"
                    style="background-image:url('{{ asset($filePath) }}/images/banner/banner-2.jpg')">

                    <div class="overlay"></div>

                    <div class="container">
                        <div class="hero-content">
                            <span class="badge bg-success">💎 Premium Quality</span>
                            <h1>Modern UI Design System</h1>
                            <p>Fast, responsive and fully customizable hero slider design.</p>

                            <div class="hero-btns">
                                <a href="#" class="btn btn-success btn-lg">Explore</a>
                                <a href="#" class="btn btn-outline-light btn-lg">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Controls -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

@push('js')
    <script>
        $(document).ready(function () {
            new Swiper(".myHeroSwiper", {
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                speed: 900,
                effect: "fade",
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        });
    </script>
@endpush
