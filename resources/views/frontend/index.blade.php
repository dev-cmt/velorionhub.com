@php
    $dealProducts = ($special_offers ?? collect())->isNotEmpty()
        ? $special_offers
        : ($hot_deals ?? collect())->filter(fn($p) => floatval($p->sale_price) < floatval($p->regular_price));
    $newArrivalChunks = ($new_arrivals ?? collect())->chunk(2);
    $recentProducts = $best_sellers ?? collect();
@endphp

<x-frontend-layout title="Home Page" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    @if($settings->is_slider)
        @include('frontend.partials.slider')
    @else
        @include('frontend.partials.hero')
    @endif

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
                                <p class="body-text-3">Free Shipping for orders over
                                    <span class="cur-price" data-price="5000">TK 5000</span>
                                </p>
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
                                <p class="body-text-3">Trusted by 2000+ people</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box wow fadeInLeft" data-wow-delay="0.4s">
                            <div class="icon-box"><i class="icon icon-check-3"></i></div>
                            <div class="content">
                                <p class="body-text fw-semibold">Guarantee</p>
                                <p class="body-text-3">Within 7 days for an exchange</p>
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
                                @include('frontend.partials.product-item-deal', ['product' => $product, 'showProgress' => true, 'wowDelay' => ($index * 0.1) . 's'])
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

    <!-- Banner Product -->
    @if(isset($promotionBanners) && $promotionBanners->isNotEmpty())
    <section>
        <div class="container">
            <div class=" swiper tf-sw-categories overflow-xxl-visible" data-preview="2" data-tablet="2"
                data-mobile-sm="1" data-mobile="1" data-space-lg="30" data-space-md="20" data-space="15"
                data-pagination="1" data-pagination-sm="2" data-pagination-md="2" data-pagination-lg="2">
                <div class="swiper-wrapper">
                    @forelse(($promotionBanners ?? collect())->take(2) as $banner)
                    <!-- item 1 -->
                    <div class="swiper-slide">
                        <a href="{{ $banner->url ?: route('shop') }}" class="banner-image-product-2 {{ $loop->iteration == 1 ? 'style-2' : '' }} type-sp-2 hover-img d-block">
                            <div class="item-image img-style overflow-visible position{{ $loop->iteration == 1 ? '3' : '2' }}">
                                <img src="{{ asset($filePath.'/images/product-'.($loop->iteration == 1 ? '1' : '2').'.png') }}" data-src="{{ asset($filePath.'/images/product-'.($loop->iteration == 1 ? '1' : '2').'.png') }}" alt="{{ $banner->title }}" class="lazyload">
                            </div>
                            <div class="item-banner has-bg-img"  data-bg-img="{{ asset($banner->images ?: $filePath.'/images/banner/banner-'.($loop->iteration == 1 ? '4' : '3').'.jpg') }}"
                                data-bg-size="cover" data-bg-repeat="no-repeat">
                                <div class="inner {{ $loop->iteration == 1 ? '' : 'justify-content-xl-end' }}">
                                    @if($banner->price)
                                        <div class="box-sale-wrap box-price type-3 relative">
                                            <p class="small-text sub-price">From</p>
                                            <p class="main-title-2 num-price">{{ $banner->price ?? '' }}</p>
                                        </div>
                                    @else
                                        <p class="mt-3">&nbsp;</p>
                                    @endif

                                    <h4 class="name fw-normal text-white lh-lg-38 text-xxl-center text-line-clamp-2">
                                        <span class="fw-bold">
                                            {{ $banner->title ?? 'Promotion' }}
                                        </span>
                                        <br class="d-none d-sm-block">
                                        {{ $banner->details ?? '' }}
                                    </h4>
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
    @endif
    <!-- /Banner Product -->

    <!-- New arrivals -->
    <section class="tf-sp-2">
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

    {{-- Dynamic sections appended by Page Builder (for the 'home' page) --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
