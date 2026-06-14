@push('css')
    <!-- Swiper Fade Effect CSS Dependency (Ensure this is loaded in your layout if not present) -->
    <style>
        :root {
            --primary-green: #ff3d3d;
            --primary-green-rgb: 255, 61, 61; /* RGB for primary green for use in rgba() */
            --white: #ffffff;
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            background-color: #000;
        }

        /* Fullscreen slide setup with absolute centering */
        .hero-slide {
            height: calc(85vh - 180px); /* Adjust based on your header height */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            overflow: hidden;
        }

        /* Isolated background layer setup */
        .hero-slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            z-index: 0;
            will-change: transform;
        }

        /* FIXED: Minimal Zoom Animations (Reduced from 1.15 to 1.06 for elegant movement) */
        .animate-zoom-in {
            animation: alternateZoomIn 5.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        .animate-zoom-out {
            animation: alternateZoomOut 5.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        /* Subtle gradient overlay to boost text readability */
        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.6) 100%);
            z-index: 1;
        }

        /* Centered content block */
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 0 20px;
        }

        /* --- Text Animation Control --- */
        .hero-content .badge-anim,
        .hero-content h1,
        .hero-content p,
        .hero-content .btn-anim {
            opacity: 0;
            will-change: transform, opacity;
        }

        /* Text entry stagger animations when active */
        .swiper-slide-active .hero-content .badge-anim {
            animation: textFadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.2s;
        }

        .swiper-slide-active .hero-content h1 {
            animation: textFadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.4s;
        }

        .swiper-slide-active .hero-content p {
            animation: textFadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.6s;
        }

        .swiper-slide-active .hero-content .btn-anim {
            animation: textFadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.8s;
        }

        .hero-content h1 {
            font-size: 56px;
            font-weight: 800;
            margin: 20px 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .hero-content p {
            font-size: 19px;
            opacity: 0.95;
            margin-bottom: 35px;
            font-weight: 400;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        /* Premium pill shape button with INFINITE pulse */
        .hero-btns .btn-custom {
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
            background-color: var(--primary-green);
            border: 2px solid var(--primary-green);
            color: var(--white);
            text-decoration: none;
            display: inline-block;
            animation: pulseBtn 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
        }

        .hero-btns .btn-custom:hover {
            animation: none; /* Pause pulse execution on hover for solid interaction state */
            transform: scale(1.05);
            background-color: transparent;
            color: var(--white);
            border-color: var(--white);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
        }

        .badge-premium {
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* --- Keyframes --- */
        @keyframes textFadeUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* FIXED: Alternating Zoom Keyframes with optimized Scale values */
        @keyframes alternateZoomIn {
            from { transform: scale(1); }
            to { transform: scale(1.06); } /* Slightly zooms in by 6% */
        }

        @keyframes alternateZoomOut {
            from { transform: scale(1.06); } /* Starts at 6% zoom */
            to { transform: scale(1); }    /* Zooms back to normal layout */
        }

        /* Infinite Button Pulse Glimmer */
        @keyframes pulseBtn {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(var(--primary-green-rgb), 0.5);
            }
            50% {
                transform: scale(1.04);
                box-shadow: 0 4px 25px rgba(var(--primary-green-rgb), 0.8);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(var(--primary-green-rgb), 0.5);
            }
        }

        /* Premium Glassmorphic Navigation Design */
        .swiper-button-next,
        .swiper-button-prev {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            width: 54px;
            height: 54px;
            color: var(--white) !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            top: 40% !important;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px !important;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--primary-green);
            border-color: var(--primary-green);
            transform: scale(1.08);
        }

        /* Unique Pill-Shaped Pagination Dots */
        .swiper-pagination-container {
            position: absolute;
            bottom: 30px !important;
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .myHeroSwiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.4) !important;
            opacity: 1 !important;
            margin: 0 6px !important;
            border-radius: 50%;
            transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
        }

        .myHeroSwiper .swiper-pagination-bullet-active {
            width: 32px !important;
            background: var(--primary-green) !important;
            border-radius: 20px !important;
            box-shadow: 0 2px 8px rgba(var(--primary-green-rgb), 0.5);
        }

        @media (max-width: 768px) {
            .hero-slide {
                height: 50vh !important;
            }
            .hero-slide-bg {
                background-image: var(--bg-mobile) !important;
            }
            .hero-content h1 {
                font-size: 36px !important;
                margin: 15px 0;
            }
            .hero-content p {
                font-size: 15px !important;
                margin-bottom: 25px;
            }
            .swiper-button-next,
            .swiper-button-prev {
                display: none !important;
            }
            .hero-btns .btn-custom {
                padding: 12px 28px;
                font-size: 15px;
            }
        }
    </style>
@endpush

<section class="hero-section">
    <div class="swiper myHeroSwiper">
        <div class="swiper-wrapper">

            @forelse($slides as $slide)
            <div class="swiper-slide">
                <div class="hero-slide">
                    <!-- Alternating background target item -->
                    <div class="hero-slide-bg" style="background-image:url('{{ asset($slide->desktop_image) }}'); --bg-mobile: url('{{ asset($slide->mobile_image ?? $slide->desktop_image) }}');"></div>
                    <div class="overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            @if($slide->offer_text)
                                <div class="mb-2 badge-anim">
                                    <span class="badge-premium">🔥 {{ $slide->offer_text }}</span>
                                </div>
                            @endif
                            <h1>{{ $slide->title }}</h1>
                            <p>{{ $slide->details }}</p>
                            <div class="hero-btns btn-anim">
                                @if($slide->button_text && $slide->button_url)
                                    <a href="{{ $slide->button_url }}" class="btn-custom">{{ $slide->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback Slide 1 -->
            <div class="swiper-slide">
                <div class="hero-slide">
                    <div class="hero-slide-bg" style="background-image:url('{{ asset($filePath) }}/images/banner/banner-1.jpg')"></div>
                    <div class="overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <div class="mb-2 badge-anim">
                                <span class="badge-premium">🔥 New Collection</span>
                            </div>
                            <h1>Build Your Dream Website</h1>
                            <p>High quality Laravel + Bootstrap UI with a modern design system.</p>
                            <div class="hero-btns btn-anim">
                                <a href="#" class="btn-custom">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse

        </div>

        <!-- Layout Controls -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination-container">
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

@push('js')
    <script>
        $(document).ready(function () {
            if (typeof Swiper !== 'undefined') {
                var heroSwiper = new Swiper(".myHeroSwiper", {
                    loop: true,
                    effect: "fade",
                    fadeEffect: {
                        crossFilter: true
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    speed: 1200,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    on: {
                        init: function () {
                            handleZoomAlternation(this);
                        },
                        slideChange: function () {
                            handleZoomAlternation(this);
                        }
                    }
                });

                function handleZoomAlternation(swiperInstance) {
                    // Clean previous states safely
                    $('.hero-slide-bg').removeClass('animate-zoom-in animate-zoom-out');

                    var activeSlide = $(swiperInstance.slides[swiperInstance.activeIndex]);
                    var bgLayer = activeSlide.find('.hero-slide-bg');

                    var realIndex = swiperInstance.realIndex;

                    if (realIndex % 2 === 0) {
                        bgLayer.addClass('animate-zoom-in');
                    } else {
                        bgLayer.addClass('animate-zoom-out');
                    }
                }
            }
        });
    </script>
@endpush
