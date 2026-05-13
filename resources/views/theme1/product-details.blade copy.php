<x-frontend-layout title="Home Page" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/photoswipe/photoswipe.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/photoswipe/default-skin/default-skin.min.css">
    @endpush

    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav container">
        <ul class="breadcrumb bb-no">
            <li><a href="demo1.html">Home</a></li>
            <li>Products</li>
        </ul>
        <ul class="product-nav list-style-none">
            <li class="product-nav-prev">
                <a href="#">
                    <i class="w-icon-angle-left"></i>
                </a>
                <span class="product-nav-popup">
                    <img src="{{asset('frontend')}}/images/products/product-nav-prev.jpg" alt="Product" width="110"
                        height="110" />
                    <span class="product-name">Soft Sound Maker</span>
                </span>
            </li>
            <li class="product-nav-next">
                <a href="#">
                    <i class="w-icon-angle-right"></i>
                </a>
                <span class="product-nav-popup">
                    <img src="{{asset('frontend')}}/images/products/product-nav-next.jpg" alt="Product" width="110"
                        height="110" />
                    <span class="product-name">Fabulous Sound Speaker</span>
                </span>
            </li>
        </ul>
    </nav>
    <!-- End of Breadcrumb -->

    <!-- Start of Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="row gutter-lg">
                <div class="main-content">
                    <div class="product product-single row mb-2">
                        {{-- Left: Product Gallery --}}
                        <div class="col-md-6 mb-4 mb-md-8">
                            <div class="product-gallery product-gallery-sticky">

                                {{-- Main Swiper --}}
                                <div class="swiper-container product-single-swiper swiper-theme nav-inner" 
                                    data-swiper-options="{
                                        'navigation': {
                                            'nextEl': '.swiper-button-next',
                                            'prevEl': '.swiper-button-prev'
                                        }
                                    }">

                                    <div class="swiper-wrapper row cols-1 gutter-no">
                                        {{-- Main Image --}}
                                        <div class="swiper-slide">
                                            <figure class="product-image">
                                                <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/default-product.png') }}"
                                                    data-zoom-image="{{ $product->main_image ? asset($product->main_image) : asset('images/default-product.png') }}"
                                                    alt="{{ $product->name }}"
                                                    width="800" height="900">
                                            </figure>
                                        </div>

                                        {{-- Gallery Images --}}
                                        @foreach($product->media as $media)
                                            <div class="swiper-slide">
                                                <figure class="product-image">
                                                    <img src="{{ asset($media->path) }}"
                                                        data-zoom-image="{{ asset($media->path) }}"
                                                        alt="{{ $media->alt_text ?? $product->name }}"
                                                        width="800" height="900">
                                                </figure>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button class="swiper-button-next"></button>
                                    <button class="swiper-button-prev"></button>
                                    <a href="#" class="product-gallery-btn product-image-full"><i class="w-icon-zoom"></i></a>
                                </div>

                                {{-- Thumbnails --}}
                                <div class="product-thumbs-wrap swiper-container" 
                                    data-swiper-options="{
                                        'navigation': {
                                            'nextEl': '.swiper-button-next',
                                            'prevEl': '.swiper-button-prev'
                                        }
                                    }">
                                    <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">

                                        {{-- Main Image Thumbnail --}}
                                        <div class="product-thumb swiper-slide">
                                            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/default-product.png') }}"
                                                alt="{{ $product->name }}" width="800" height="900">
                                        </div>

                                        {{-- Gallery Thumbnails --}}
                                        @foreach($product->media as $media)
                                            <div class="product-thumb swiper-slide">
                                                <img src="{{ asset($media->path) }}"
                                                    alt="{{ $media->alt_text ?? $product->name }}" width="800" height="900">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="swiper-button-next"></button>
                                    <button class="swiper-button-prev"></button>
                                </div>

                            </div>
                        </div>

                        {{-- Right: Product Details --}}
                        <div class="col-md-6 mb-6 mb-md-8">
                            <div class="product-details" data-sticky-options="{'minWidth': 767}">

                                {{-- Product Title --}}
                                <h1 class="product-title">{{ $product->name }}</h1>

                                {{-- Brand & Category --}}
                                <div class="product-bm-wrapper">
                                    @if($product->brand)
                                        <figure class="brand">
                                            <img src="{{ asset($product->brand->logo ?? 'images/default-brand.png') }}"
                                                alt="{{ $product->brand->name }}" width="106" height="48" />
                                        </figure>
                                    @endif

                                    <div class="product-meta">
                                        <div class="product-categories">
                                            Category:
                                            @if($product->category)
                                                <span class="product-category">
                                                    <a href="#">{{ $product->category->name }}</a>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="product-sku">
                                            SKU: <span>{{ $product->sku }}</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="product-divider">

                                {{-- Price --}}
                                <div class="product-price">
                                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                                        <ins class="new-price">${{ number_format($product->sale_price,2) }}</ins>
                                        <del class="old-price">${{ number_format($product->regular_price,2) }}</del>
                                    @else
                                        <ins class="new-price">${{ number_format($product->regular_price,2) }}</ins>
                                    @endif
                                </div>

                                {{-- Ratings --}}
                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        @php
                                            $ratingPercent = ($product->reviews_avg_rating ?? 0) * 20;
                                        @endphp
                                        <span class="ratings" style="width: {{ $ratingPercent }}%;"></span>
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <a href="#product-tab-reviews" class="rating-reviews">
                                        ({{ $product->reviews_count ?? 0 }} Reviews)
                                    </a>
                                </div>

                                {{-- Short Description --}}
                                <div class="product-short-desc">
                                    <ul class="list-type-check list-style-none">
                                        {!! $product->short_description ?? '' !!}
                                    </ul>
                                </div>

                                <hr class="product-divider">

                                {{-- Add to Cart --}}
                                <div class="fix-bottom product-sticky-content sticky-content">
                                    <div class="product-form container">

                                        <div class="product-qty-form">
                                            <div class="input-group">
                                                <button class="quantity-minus w-icon-minus"></button>
                                                <input class="quantity form-control" type="number" value="1" min="1">
                                                <button class="quantity-plus w-icon-plus"></button>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary btn-cart"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->sale_price ?? $product->regular_price }}"
                                            data-image="{{ asset($product->main_image) }}"
                                            data-url="{{ route('product.show', $product->slug) }}">
                                            <i class="w-icon-cart"></i>
                                            <span>Add to Cart</span>
                                        </button>

                                    </div>
                                </div>


                                {{-- Social & Wishlist --}}
                                <div class="social-links-wrapper">
                                    <div class="social-links">
                                        <div class="social-icons social-no-color border-thin">
                                            <a href="#" class="social-icon social-facebook w-icon-facebook"></a>
                                            <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                                            <a href="#" class="social-icon social-pinterest fab fa-pinterest-p"></a>
                                            <a href="#" class="social-icon social-whatsapp fab fa-whatsapp"></a>
                                            <a href="#" class="social-icon social-youtube fab fa-linkedin-in"></a>
                                        </div>
                                    </div>
                                    <span class="divider d-xs-show"></span>
                                    <div class="product-link-wrapper d-flex">
                                        <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"><span></span></a>
                                        <a href="#" class="btn-product-icon btn-compare btn-icon-left w-icon-compare"><span></span></a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <section class="description-section">
                        <div class="title-link-wrapper no-link">
                            <h2 class="title title-link">Description</h2>
                        </div>
                        <div class="pt-4 pb-1" id="product-tab-description">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                        sed do eiusmod tempor incididunt arcu cursus vitae congue mauris.
                                        Sagittis id consectetur purus ut. Tellus rutrum tellus pelle Vel
                                        pretium lectus quam id leo in vitae turpis massa.</p>
                                    <ul class="list-type-check list-style-none pl-0">
                                        <li>Nunc nec porttitor turpis. In eu risus enim. In vitae mollis elit.
                                        </li>
                                        <li>Vivamus finibus vel mauris ut vehicula.</li>
                                        <li>Nullam a magna porttitor, dictum risus nec, faucibus sapien.</li>
                                        <li>Ultrices eros in cursus turpis massa tincidunt ante in nibh mauris.
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="banner banner-video product-video br-xs">
                                        <figure class="banner-media">
                                            <a href="#">
                                                <img src="{{asset('frontend')}}/images/products/video-banner-610x300.jpg"
                                                    alt="banner" width="610" height="300"
                                                    style="background-color: #bebebe;">
                                            </a>
                                            <a class="btn-play-video btn-iframe"
                                                href="assets/video/memory-of-a-woman.mp4"></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <div class="row cols-md-3 mt-5">
                                <div class="mb-3">
                                    <h5 class="sub-title font-weight-bold"><span class="mr-3">1.</span>Free
                                        Shipping &amp; Return</h5>
                                    <p class="detail pl-5">We offer free shipping for products on orders above
                                        50$ and offer free delivery for all orders in US.</p>
                                </div>
                                <div class="mb-3">
                                    <h5 class="sub-title font-weight-bold"><span>2.</span>Free and Easy Returns
                                    </h5>
                                    <p class="detail pl-5">We guarantee our products and you could get back all
                                        of your money anytime you want in 30 days.</p>
                                </div>
                                <div class="mb-3">
                                    <h5 class="sub-title font-weight-bold"><span>3.</span>Special Financing</h5>
                                    <p class="detail pl-5">Get 20%-50% off items over 50$ for a month or over
                                        250$ for a year with our special credit card.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="review-section">
                        <div class="title-link-wrapper no-link">
                            <h2 class="title title-link">Customer Reviews</h2>
                        </div>
                        <div class="pt-4 pb-1" id="product-tab-reviews">
                            <div class="row mb-4">
                                <div class="col-xl-4 col-lg-5 mb-4">
                                    <div class="ratings-wrapper">
                                        <div class="avg-rating-container">
                                            <h4 class="avg-mark font-weight-bolder ls-50">3.3</h4>
                                            <div class="avg-rating">
                                                <p class="text-dark mb-1">Average Rating</p>
                                                <div class="ratings-container">
                                                    <div class="ratings-full">
                                                        <span class="ratings" style="width: 60%;"></span>
                                                        <span class="tooltiptext tooltip-top"></span>
                                                    </div>
                                                    <a href="#" class="rating-reviews">(3 Reviews)</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ratings-value d-flex align-items-center text-dark ls-25">
                                            <span
                                                class="text-dark font-weight-bold">66.7%</span>Recommended<span
                                                class="count">(2 of 3)</span>
                                        </div>
                                        <div class="ratings-list">
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 100%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <div class="progress-bar progress-bar-sm ">
                                                    <span></span>
                                                </div>
                                                <div class="progress-value">
                                                    <mark>70%</mark>
                                                </div>
                                            </div>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 80%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <div class="progress-bar progress-bar-sm ">
                                                    <span></span>
                                                </div>
                                                <div class="progress-value">
                                                    <mark>30%</mark>
                                                </div>
                                            </div>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 60%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <div class="progress-bar progress-bar-sm ">
                                                    <span></span>
                                                </div>
                                                <div class="progress-value">
                                                    <mark>40%</mark>
                                                </div>
                                            </div>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 40%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <div class="progress-bar progress-bar-sm ">
                                                    <span></span>
                                                </div>
                                                <div class="progress-value">
                                                    <mark>0%</mark>
                                                </div>
                                            </div>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 20%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <div class="progress-bar progress-bar-sm ">
                                                    <span></span>
                                                </div>
                                                <div class="progress-value">
                                                    <mark>0%</mark>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-7 mb-4">
                                    <div class="review-form-wrapper">
                                        <h3 class="title tab-pane-title font-weight-bold mb-1">Submit Your
                                            Review</h3>
                                        <p class="mb-3">Your email address will not be published. Required
                                            fields are marked *</p>
                                        <form action="#" method="POST" class="review-form">
                                            <div class="rating-form">
                                                <label for="rating">Your Rating Of This Product :</label>
                                                <span class="rating-stars">
                                                    <a class="star-1" href="#">1</a>
                                                    <a class="star-2" href="#">2</a>
                                                    <a class="star-3" href="#">3</a>
                                                    <a class="star-4" href="#">4</a>
                                                    <a class="star-5" href="#">5</a>
                                                </span>
                                                <select name="rating" id="rating" required=""
                                                    style="display: none;">
                                                    <option value="">Rate…</option>
                                                    <option value="5">Perfect</option>
                                                    <option value="4">Good</option>
                                                    <option value="3">Average</option>
                                                    <option value="2">Not that bad</option>
                                                    <option value="1">Very poor</option>
                                                </select>
                                            </div>
                                            <textarea cols="30" rows="6" placeholder="Write Your Review Here..."
                                                class="form-control" id="review"></textarea>
                                            <div class="row gutter-md">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        placeholder="Your Name" id="author">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        placeholder="Your Email" id="email_1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="custom-checkbox"
                                                    id="save-checkbox">
                                                <label for="save-checkbox">Save my name, email, and website in
                                                    this browser for the next time I comment.</label>
                                            </div>
                                            <button type="submit" class="btn btn-dark">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- End of Main Content -->
                <aside class="sidebar product-sidebar sidebar-fixed right-sidebar sticky-sidebar-wrapper">
                    <div class="sidebar-overlay"></div>
                    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                    <a href="#" class="sidebar-toggle d-flex d-lg-none"><i class="fas fa-chevron-left"></i></a>
                    <div class="sidebar-content scrollable">
                        <div class="sticky-sidebar">
                            <div class="widget widget-icon-box mb-6">
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-dark">
                                        <i class="w-icon-truck"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Free Shipping & Returns</h4>
                                        <p>For all orders over $99</p>
                                    </div>
                                </div>
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-dark">
                                        <i class="w-icon-bag"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Secure Payment</h4>
                                        <p>We ensure secure payment</p>
                                    </div>
                                </div>
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-dark">
                                        <i class="w-icon-money"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Money Back Guarantee</h4>
                                        <p>Any back within 30 days</p>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Widget Icon Box -->

                            <div class="widget widget-banner mb-9">
                                <div class="banner banner-fixed br-sm">
                                    <figure>
                                        <img src="{{asset('frontend')}}/images/shop/banner3.jpg" alt="Banner" width="266"
                                            height="220" style="background-color: #1D2D44;" />
                                    </figure>
                                    <div class="banner-content">
                                        <div class="banner-price-info font-weight-bolder text-white lh-1 ls-25">
                                            40<sup class="font-weight-bold">%</sup><sub
                                                class="font-weight-bold text-uppercase ls-25">Off</sub>
                                        </div>
                                        <h4
                                            class="banner-subtitle text-white font-weight-bolder text-uppercase mb-0">
                                            Ultimate Sale</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Widget Banner -->

                            <div class="widget widget-products">
                                <div class="title-link-wrapper mb-2">
                                    <h4 class="title title-link font-weight-bold">More Products</h4>
                                </div>
                            
                                <div class="swiper nav-top">
                                    <div class="swiper-container swiper-theme nav-top" data-swiper-options = "{
                                        'slidesPerView': 1,
                                        'spaceBetween': 20,
                                        'navigation': {
                                            'prevEl': '.swiper-button-prev',
                                            'nextEl': '.swiper-button-next'
                                        }
                                    }">
                                        <div class="swiper-wrapper">
                                            <div class="widget-col swiper-slide">
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/13.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">Smart Watch</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 100%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$80.00 - $90.00</div>
                                                    </div>
                                                </div>
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/14.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">Sky Medical Facility</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 80%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$58.00</div>
                                                    </div>
                                                </div>
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/15.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">Black Stunt Motor</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 60%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$374.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-col swiper-slide">
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/16.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">Skate Pan</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 100%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$278.00</div>
                                                    </div>
                                                </div>
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/17.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">Modern Cooker</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 80%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$324.00</div>
                                                    </div>
                                                </div>
                                                <div class="product product-widget">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{asset('frontend')}}/images/shop/18.jpg" alt="Product"
                                                                width="100" height="113" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name">
                                                            <a href="#">CT Machine</a>
                                                        </h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 100%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">$236.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="swiper-button-next"></button>
                                        <button class="swiper-button-prev"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <!-- End of Sidebar -->
            </div>
        </div>
    </div>
    <!-- End of Page Content -->

    @push('js')
        <script src="{{asset('frontend')}}/vendor/sticky/sticky.min.js"></script>
        <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe.min.js"></script>
        <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe-ui-default.min.js"></script>
    @endpush
</x-frontend-layout>
