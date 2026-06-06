@php
    $featuredProduct = ($special_offers ?? collect())->first() ?? ($hot_deals ?? collect())->first();
    $bannerSideProducts = ($hot_deals ?? collect())
        ->when($featuredProduct, fn($c) => $c->where('id', '!=', $featuredProduct->id))
        ->take(2);
    $dealProducts = ($special_offers ?? collect())->isNotEmpty()
        ? $special_offers
        : ($hot_deals ?? collect())->filter(fn($p) => floatval($p->sale_price) < floatval($p->regular_price));
    $newArrivalChunks = ($new_arrivals ?? collect())->chunk(2);
    $recentProducts = $best_sellers ?? collect();

    // Pre-compute featured product variables for use in the banner section.
    // These cannot be sourced from the @include partial because Blade partials
    // do not propagate variables back to the parent template scope.
    $productUrl  = null;
    $mainImage   = null;
    $categoryName = null;
    if ($featuredProduct) {
        $productUrl   = route('product.show', $featuredProduct->slug);
        $mainImage    = $featuredProduct->main_image ? asset($featuredProduct->main_image) : asset('images/no-image.jpg');
        $categoryName = $featuredProduct->category->name ?? 'Uncategorized';
    }
@endphp

<x-frontend-layout title="Home Page" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Banner Product -->
    <section class="has-bg-img" data-bg-img="{{ asset('frontend') }}/images/banner/banner-6.jpg">
        <div class="container">
            <div class="banner-product flex-xl-nowrap justify-content-center">
                @if($featuredProduct)
                    <div class="product-wrap hover-img flex-md-nowrap justify-content-center">
                        <a href="{{ $productUrl }}" class="d-inline-flex item-product img-style">
                            <img src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $featuredProduct->name }}" class="lazyload">
                        </a>
                        <div class="info-product text-center text-md-start">
                            <div class="box-title">
                                <p class="tag-new text-white text-uppercase title-sidebar">New arrival</p>
                                <h1 class="name">
                                    <a href="{{ $productUrl }}" class="text-white text-uppercase link">
                                        {{ $categoryName }}
                                    </a>
                                </h1>
                            </div>
                            <div class="box-price">
                                <p class="start text-white">Starting</p>
                                <h1 class="price text-primary">TK {{ number_format($featuredProduct->sale_price, 2) }}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="other-item flex-xl-column flex-md-row">
                    @foreach($bannerSideProducts as $product)
                        @include('frontend.partials.product-item-horizontal', ['product' => $product, 'bgWhite' => true])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Banner Product -->

    <!-- Iconbox -->
    <div class="tf-sp-2">
        <div class="container">
            <div class="swiper tf-sw-iconbox" data-preview="5" data-tablet="3" data-mobile-sm="2" data-mobile="1"
                data-space-lg="20" data-space-md="20" data-space="15">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0s">
                            <div class="icon-box"><i class="icon icon-delivery-2"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Free delivery</p>
                                <p class="body-text-3">Free Shipping for orders over $20</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0.1s">
                            <div class="icon-box"><i class="icon icon-support-2"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Support 24/7</p>
                                <p class="body-text-3">24 hours a day, 7 days a week</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0.2s">
                            <div class="icon-box"><i class="icon icon-payment"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Payment</p>
                                <p class="body-text-3">Pay with Multiple Credit Cards</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0.3s">
                            <div class="icon-box"><i class="icon icon-reliable"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Reliable</p>
                                <p class="body-text-3">Trusted by 2000+ major brands</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0.4s">
                            <div class="icon-box"><i class="icon icon-check-3"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Guarantee</p>
                                <p class="body-text-3">Within 30 days for an exchange</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-pagination-iconbox sw-dot-default justify-content-center"></div>
            </div>
        </div>
    </div>
    <!-- /Iconbox -->

    <!-- Deal Today -->
    <section class="tf-sp-2 pt-0">
        <div class="container">
            <div class="flat-title pb-8 wow fadeInUp" data-wow-delay="0s">
                <h5 class="fw-semibold text-primary flat-title-has-icon">
                    <span class="icon"><i class="icon-fire tf-ani-tada"></i></span>Deal Of The Day
                </h5>
                <div class="box-btn-slide relative">
                    <div class="swiper-button-prev nav-swiper nav-prev-products"><i class="icon-arrow-left-lg"></i></div>
                    <div class="swiper-button-next nav-swiper nav-next-products"><i class="icon-arrow-right-lg"></i></div>
                </div>
            </div>
            <div class="box-btn-slide-2 sw-nav-effect">
                <div class="swiper tf-sw-products" data-preview="5" data-tablet="4" data-mobile-sm="3"
                    data-mobile="2" data-space-lg="30" data-space-md="20" data-space="15" data-pagination="2"
                    data-pagination-sm="3" data-pagination-md="4" data-pagination-lg="5">
                    <div class="swiper-wrapper">
                        @forelse($dealProducts as $index => $product)
                            <div class="swiper-slide">
                                @include('frontend.partials.product-item-deal', [
                                    'product' => $product,
                                    'wowDelay' => ($index * 0.1) . 's',
                                ])
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <p class="body-text text-center py-4">No deals available right now.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="sw-dot-default sw-pagination-products justify-content-center"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Deal Today -->

    <!-- Promotion Banners -->
    <section>
        <div class="container">
            <div class="swiper tf-sw-categories overflow-xxl-visible" data-preview="2" data-tablet="2"
                data-mobile-sm="1" data-mobile="1" data-space-lg="30" data-space-md="20" data-space="15"
                data-pagination="1" data-pagination-sm="2" data-pagination-md="2" data-pagination-lg="2">
                <div class="swiper-wrapper">
                    @forelse(($promotionBanners ?? collect())->take(2) as $banner)
                        <div class="swiper-slide">
                            <a href="{{ $banner->url ?: route('shop') }}"
                                class="banner-image-product-2 type-sp-2 hover-img d-block {{ $loop->iteration === 1 ? 'style-2' : '' }}">
                                <div class="item-image img-style overflow-visible {{ $loop->iteration === 1 ? 'position3' : 'position2' }}">
                                    <img src="{{ asset($banner->image) }}" data-src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="lazyload">
                                </div>
                                <div class="item-banner has-bg-img" data-bg-img="{{ asset('frontend') }}/images/banner/banner-{{ $loop->iteration === 1 ? '4' : '3' }}.jpg"
                                    data-bg-size="cover" data-bg-repeat="no-repeat">
                                    <div class="inner {{ $loop->iteration === 2 ? 'justify-content-xl-end' : '' }}">
                                        @if($banner->details)
                                            <div class="box-sale-wrap {{ $loop->iteration === 1 ? 'box-price' : '' }} type-3 relative">
                                                <p class="small-text {{ $loop->iteration === 1 ? 'sub-price' : '' }}">{{ $banner->details }}</p>
                                                @if($banner->button_text)
                                                    <p class="main-title-2 {{ $loop->iteration === 1 ? 'num-price' : '' }}">{{ $banner->button_text }}</p>
                                                @endif
                                            </div>
                                        @endif
                                        @if($banner->title)
                                            <h4 class="name fw-normal text-white lh-lg-38 {{ $loop->iteration === 1 ? 'text-xxl-center text-line-clamp-2' : 'text-xl-end' }}">
                                                {!! nl2br(e($banner->title)) !!}
                                            </h4>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        @foreach(($categories ?? collect())->take(2) as $category)
                            <div class="swiper-slide">
                                <a href="{{ route('shop', ['category' => $category->slug]) }}"
                                    class="banner-image-product-2 type-sp-2 hover-img d-block">
                                    <div class="item-banner has-bg-img" data-bg-img="{{ asset('frontend') }}/images/banner/banner-{{ $loop->iteration === 1 ? '4' : '3' }}.jpg"
                                        data-bg-size="cover" data-bg-repeat="no-repeat">
                                        <div class="inner">
                                            <h4 class="name fw-normal text-white lh-lg-38">{{ $category->name }}</h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforelse
                </div>
                <div class="sw-dot-default sw-pagination-categories justify-content-center"></div>
            </div>
        </div>
    </section>
    <!-- /Promotion Banners -->

    <!-- New arrivals -->
    <section class="tf-sp-2 pt-0">
        <div class="container">
            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                <h5 class="fw-semibold">New arrivals</h5>
                <div class="box-btn-slide relative">
                    <div class="swiper-button-prev nav-swiper nav-prev-products"><i class="icon-arrow-left-lg"></i></div>
                    <div class="swiper-button-next nav-swiper nav-next-products"><i class="icon-arrow-right-lg"></i></div>
                </div>
            </div>
            <div class="swiper tf-sw-products" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="1"
                data-space-lg="30" data-space-md="20" data-space="15" data-pagination="1" data-pagination-sm="2"
                data-pagination-md="3" data-pagination-lg="4">
                <div class="swiper-wrapper">
                    @forelse($newArrivalChunks as $index => $chunk)
                        <div class="swiper-slide">
                            <ul class="product-list-wrap wow fadeInUp" data-wow-delay="{{ ($index * 0.1) }}s">
                                @foreach($chunk as $product)
                                    <li>
                                        @include('frontend.partials.product-item-horizontal', ['product' => $product])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <p class="body-text text-center py-4">No new arrivals yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="sw-dot-default sw-pagination-products justify-content-center"></div>
            </div>
        </div>
    </section>
    <!-- /New arrivals -->

    <!-- Recently Viewed -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                <h5 class="fw-semibold">Recently Viewed</h5>
                <div class="box-btn-slide relative">
                    <div class="swiper-button-prev nav-swiper nav-prev-products"><i class="icon-arrow-left-lg"></i></div>
                    <div class="swiper-button-next nav-swiper nav-next-products"><i class="icon-arrow-right-lg"></i></div>
                </div>
            </div>
            <div class="swiper tf-sw-products" data-preview="5" data-tablet="4" data-mobile-sm="3" data-mobile="2"
                data-space-lg="30" data-space-md="20" data-space="15" data-pagination="2" data-pagination-sm="3"
                data-pagination-md="4" data-pagination-lg="5">
                <div class="swiper-wrapper">
                    @forelse($recentProducts as $index => $product)
                        <div class="swiper-slide">
                            @include('frontend.partials.product-item-deal', [
                                'product' => $product,
                                'wowDelay' => ($index * 0.1) . 's',
                            ])
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <p class="body-text text-center py-4">No products to show.</p>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex d-lg-none sw-dot-default sw-pagination-products justify-content-center"></div>
            </div>
        </div>
    </section>
    <!-- /Recently Viewed -->
</x-frontend-layout>
