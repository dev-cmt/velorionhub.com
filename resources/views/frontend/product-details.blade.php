@php
    use Darryldecode\Cart\Facades\CartFacade as Cart;
    use Illuminate\Support\Facades\Auth;

    $mainImage    = $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
    $productUrl   = route('product.show', $product->slug);
    $isSale       = floatval($product->sale_price) < floatval($product->regular_price);
    $discountPct  = 0;
    if ($isSale && floatval($product->regular_price) > 0) {
        $discountPct = round(((floatval($product->regular_price) - floatval($product->sale_price)) / floatval($product->regular_price)) * 100);
    }

    // Media gallery from polymorphic relationship
    $mediaImages = $product->media->where('type', 'image')->sortBy('sort_order')->values();

    // Wishlist / Compare status
    $inWishlist  = false;
    $inCompare   = false;
    try {
        $sessionId   = (Auth::id() ?? session()->getId());
        $wishlistCart = Cart::session($sessionId . '_wishlist');
        if ($wishlistCart && $wishlistCart->get($product->id)) { $inWishlist = true; }
        $compareCart  = Cart::session($sessionId . '_compare');
        if ($compareCart && $compareCart->get($product->id))  { $inCompare  = true; }
    } catch (\Exception $e) {}

    // Reviews
    $reviews       = $product->reviews()->where('status', 1)->latest()->get();
    $reviewCount   = $reviews->count();
    $avgRating     = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;
    $ratingDistrib = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
    foreach ($reviews as $r) { if (isset($ratingDistrib[$r->rating])) { $ratingDistrib[$r->rating]++; } }

    // Specifications
    $specs = is_array($product->specification) ? $product->specification : [];
@endphp

<x-frontend-layout title="{{ $product->name }}" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    @push('css')
        <link rel="stylesheet" href="{{asset($filePath)}}/css/photoswipe.css">
        <link rel="stylesheet" href="{{asset($filePath)}}/css/drift-basic.min.css">
    @endpush

    <!-- Breadcrumbs -->
    <div class="tf-sp-1">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">Home</a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <a href="{{ route('shop') }}" class="body-small link">Shop</a>
                </li>
                @if($product->category)
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="body-small link">
                        {{ $product->category->name }}
                    </a>
                </li>
                @endif
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">{{ $product->name }}</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs -->

    <!-- Product Main -->
    <section>
        <div class="tf-main-product section-image-zoom border-bt">
            <div class="container">
                <div class="row">

                    <!-- ── Product Gallery ──────────────────────────────── -->
                    <div class="col-md-6">
                        <div class="tf-product-media-wrap thumbs-left sticky-top">
                            <div class="thumbs-slider flex-xl-row flex-column-reverse">

                                {{-- Thumbnails --}}
                                <div class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical">
                                    <div class="swiper-wrapper stagger-wrap">
                                        {{-- Main image thumbnail --}}
                                        <div class="swiper-slide stagger-item">
                                            <div class="item">
                                                <img class="lazyload" src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                        {{-- Additional media thumbnails --}}
                                        @foreach($mediaImages as $media)
                                            <div class="swiper-slide stagger-item">
                                                <div class="item">
                                                    <img class="lazyload" src="{{ asset($media->path) }}" data-src="{{ asset($media->path) }}" alt="{{ $product->name }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Main swiper --}}
                                <div class="swiper tf-product-media-main" id="gallery-swiper-started">
                                    <div class="swiper-wrapper">
                                        {{-- Main image slide --}}
                                        <div class="swiper-slide">
                                            <a href="{{ $mainImage }}" target="_blank"
                                               class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                     src="{{ $mainImage }}"
                                                     data-zoom="{{ $mainImage }}"
                                                     data-src="{{ $mainImage }}"
                                                     alt="{{ $product->name }}">
                                            </a>
                                        </div>
                                        {{-- Additional media slides --}}
                                        @foreach($mediaImages as $media)
                                            <div class="swiper-slide">
                                                <a href="{{ asset($media->path) }}" target="_blank"
                                                   class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                    <img class="tf-image-zoom lazyload"
                                                         src="{{ asset($media->path) }}"
                                                         data-zoom="{{ asset($media->path) }}"
                                                         data-src="{{ asset($media->path) }}"
                                                         alt="{{ $product->name }}">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- ── /Product Gallery ─────────────────────────────── -->

                    <!-- ── Product Info ─────────────────────────────────── -->
                    <div class="col-md-6">
                        <div class="tf-product-info-wrap position-relative">
                            <div class="tf-zoom-main"></div>
                            <div class="tf-product-info-list style-2">
                                <div class="tf-product-info-content">

                                    {{-- Heading --}}
                                    <div class="infor-heading">
                                        <p class="caption">
                                            Category:
                                            @if($product->category)
                                                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="link text-secondary">
                                                    {{ $product->category->name }}
                                                </a>
                                            @else
                                                <span>Uncategorized</span>
                                            @endif
                                        </p>
                                        <h5 class="product-info-name fw-semibold">{{ $product->name }}</h5>
                                        <ul class="product-info-rate-wrap">
                                            <li class="star-review">
                                                <ul class="list-star">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <li><i class="icon-star {{ $i <= $avgRating ? '' : 'text-main-4' }}"></i></li>
                                                    @endfor
                                                </ul>
                                                <p class="caption text-main-2">Reviews ({{ $reviewCount }})</p>
                                            </li>
                                            @if($product->sku)
                                            <li>
                                                <p class="caption text-main-2">SKU: {{ $product->sku }}</p>
                                            </li>
                                            @endif
                                            <li class="d-flex">
                                                <a href="{{ route('shop') }}" class="caption text-secondary link">View shop</a>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Price --}}
                                    <div class="infor-center">
                                        <div class="product-info-price">
                                            <h4 class="text-primary cur-price" data-price="{{ $product->sale_price }}">
                                                TK {{ number_format($product->sale_price, 2) }}
                                            </h4>
                                            @if($isSale)
                                                <span class="price-text text-main-2 old-price cur-price" data-price="{{ $product->regular_price }}">
                                                    TK {{ number_format($product->regular_price, 2) }}
                                                </span>
                                                <span class="badge bg-danger ms-2">-{{ $discountPct }}%</span>
                                            @endif
                                        </div>

                                        {{-- Stock status --}}
                                        <div class="mt-1">
                                            @if($product->total_stock > 0)
                                                <span class="badge bg-success">In Stock ({{ $product->total_stock }} available)</span>
                                            @else
                                                <span class="badge bg-secondary">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Specifications / Features --}}
                                    @if(!empty($specs))
                                    <ul class="product-fearture-list">
                                        @foreach(array_slice($specs, 0, 5) as $key => $val)
                                            <li>
                                                <p class="body-md-2 fw-semibold">{{ $key }}</p>
                                                <span class="body-text-3">{{ $val }}</span>
                                            </li>
                                        @endforeach
                                        @if($product->brand)
                                        <li>
                                            <p class="body-md-2 fw-semibold">Brand</p>
                                            <span class="body-text-3">{{ $product->brand->name }}</span>
                                        </li>
                                        @endif
                                    </ul>
                                    @elseif($product->brand)
                                    <ul class="product-fearture-list">
                                        <li>
                                            <p class="body-md-2 fw-semibold">Brand</p>
                                            <span class="body-text-3">{{ $product->brand->name }}</span>
                                        </li>
                                    </ul>
                                    @endif

                                    {{-- Add to Cart & Quantity --}}
                                    <div class="">
                                        <div class="tf-product-info-choose-option flex-xl-nowrap">
                                            <div class="product-quantity">
                                                <p class="title body-text-3">Quantity</p>
                                                <div class="wg-quantity">
                                                    <button type="button" class="btn-quantity btn-decrease" id="btn-decrease">
                                                        <i class="icon-minus"></i>
                                                    </button>
                                                    <input class="quantity-product" id="product-qty" type="number" name="qty" value="1" min="1" max="{{ max(1, $product->total_stock) }}">
                                                    <button type="button" class="btn-quantity btn-increase" id="btn-increase">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-box-btn">
                                                @if($product->total_stock > 0)
                                                    <button class="tf-btn text-white order-now"
                                                       data-id="{{ $product->id }}"
                                                       data-name="{{ $product->name }}"
                                                       data-price="{{ $product->sale_price }}"
                                                       data-image="{{ $mainImage }}"
                                                       data-url="{{ $productUrl }}">
                                                        Buy Now
                                                        <i class="icon-cart-2"></i>
                                                    </button>
                                                    <a href="#shoppingCart" data-bs-toggle="offcanvas"
                                                       class="tf-btn btn-line add-to-cart"
                                                       data-id="{{ $product->id }}"
                                                       data-name="{{ $product->name }}"
                                                       data-price="{{ $product->sale_price }}"
                                                       data-image="{{ $mainImage }}"
                                                       data-url="{{ $productUrl }}">
                                                        Add to cart
                                                    </a>
                                                @else
                                                    <button class="tf-btn text-white" disabled style="opacity:0.6; cursor:not-allowed;">
                                                        Out of Stock
                                                    </button>
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Wishlist & Compare buttons --}}
                                    <div class="d-flex gap-2">
                                        <a href="#;" class="tf-btn-icon style-2 type-black {{ $inWishlist ? 'active' : '' }}"
                                            data-action="wishlist"
                                            data-id="{{ $product->id }}"
                                            id="product-detail-wishlist"
                                            title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                            <i class="icon-heart2"></i>
                                            <span class="body-text-3 fw-normal ms-1">{{ $inWishlist ? 'In Wishlist' : 'Wishlist' }}</span>
                                        </a>
                                        <a href="#compare" data-bs-toggle="offcanvas"
                                            class="tf-btn-icon style-2 type-black {{ $inCompare ? 'active' : '' }}"
                                            data-action="compare"
                                            data-id="{{ $product->id }}"
                                            id="product-detail-compare"
                                            title="{{ $inCompare ? 'Remove from Compare' : 'Add to Compare' }}">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 6.5V9V6.5ZM9 9V11.5V9ZM9 9H11.5H9ZM9 9H6.5H9ZM16.5 9C16.5 9.98491 16.306 10.9602 15.9291 11.8701C15.5522 12.7801 14.9997 13.6069 14.3033 14.3033C13.6069 14.9997 12.7801 15.5522 11.8701 15.9291C10.9602 16.306 9.98491 16.5 9 16.5C8.01509 16.5 7.03982 16.306 6.12987 15.9291C5.21993 15.5522 4.39314 14.9997 3.6967 14.3033C3.00026 13.6069 2.44781 12.7801 2.0709 11.8701C1.69399 10.9602 1.5 9.98491 1.5 9C1.5 7.01088 2.29018 5.10322 3.6967 3.6967C5.10322 2.29018 7.01088 1.5 9 1.5C10.9891 1.5 12.8968 2.29018 14.3033 3.6967C15.7098 5.10322 16.5 7.01088 16.5 9Z"
                                                    stroke="#004EC3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="body-text-3 fw-normal ms-1">Compare</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ── /Product Info ─────────────────────────────────── -->

                </div>
            </div>
        </div>
    </section>
    <!-- /Product Main -->

    <!-- Product Description Tab -->
    <section class="tf-sp-4">
        <div class="container">
            <div class="flat-product-des-list style-2">

                {{-- Description --}}
                @if($product->description)
                <div class="flat-title-tab-product-des">
                    <h5 class="fw-semibold">Description</h5>
                    <div class="tab-main tab-des">
                        <div class="body-text-3">{!! nl2br(e($product->description)) !!}</div>
                    </div>
                </div>
                @endif

                {{-- Specifications --}}
                @if(!empty($specs))
                <div class="flat-title-tab-product-des">
                    <h5 class="fw-semibold">Product information</h5>
                    <div class="tab-main tab-info">
                        <ul class="list-feature">
                            @foreach($specs as $key => $val)
                                <li>
                                    <p class="name-feature">{{ $key }}</p>
                                    <p class="property">{{ $val }}</p>
                                </li>
                            @endforeach
                            @if($product->sku)
                            <li>
                                <p class="name-feature">SKU</p>
                                <p class="property">{{ $product->sku }}</p>
                            </li>
                            @endif
                            @if($product->brand)
                            <li>
                                <p class="name-feature">Brand</p>
                                <p class="property">{{ $product->brand->name }}</p>
                            </li>
                            @endif
                            @if($product->manufacturer)
                            <li>
                                <p class="name-feature">Manufacturer</p>
                                <p class="property">{{ $product->manufacturer }}</p>
                            </li>
                            @endif
                            <li>
                                <p class="name-feature">Customer Reviews</p>
                                <div class="w-100 star-review flex-wrap">
                                    <ul class="list-star">
                                        @for($i = 1; $i <= 5; $i++)
                                            <li><i class="icon-star {{ $i <= $avgRating ? '' : 'text-main-4' }}"></i></li>
                                        @endfor
                                    </ul>
                                    <p class="caption text-main-2">Reviews ({{ $reviewCount }})</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Reviews --}}
                <div class="flat-title-tab-product-des">
                    <h5 class="fw-semibold">Reviews</h5>
                    <div class="tab-main tab-review style-2">

                        {{-- Rating Summary --}}
                        <div class="tab-rating-wrap">
                            <div class="li rating-percent flex-shrink-0">
                                <p class="rate-percent">{{ number_format($avgRating, 1) }} <span>/ 5</span></p>
                                <ul class="list-star justify-content-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <li><i class="icon-star {{ $i <= $avgRating ? '' : 'text-main-4' }}"></i></li>
                                    @endfor
                                </ul>
                                <p class="text-cl-3">Based on {{ $reviewCount }} review{{ $reviewCount !== 1 ? 's' : '' }}</p>
                            </div>
                            <span class="br-line d-none d-xl-block type-vertical"></span>
                            <ul class="li rating-progress-list flex-shrink-0">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    @php
                                        $pct = $reviewCount > 0 ? round(($ratingDistrib[$star] / $reviewCount) * 100) : 0;
                                    @endphp
                                    <li>
                                        <p class="start-number body-text-3">{{ $star }}<i class="icon-star text-third"></i></p>
                                        <div class="rating-progress">
                                            <div class="progress style-2" role="progressbar">
                                                <div class="progress-bar" style="width: {{ $pct }}%;"></div>
                                            </div>
                                        </div>
                                        <p class="count-review body-text-3">{{ $ratingDistrib[$star] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Review List --}}
                        <div class="tab-review-wrap">
                            @if(session('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif

                            <ul class="review-list">
                                @forelse($reviews as $review)
                                    <li class="box-review">
                                        <div class="avt">
                                            <div style="width:48px;height:48px;border-radius:50%;background:#004EC3;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;">
                                                {{ strtoupper(substr($review->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <div class="author-wrap">
                                                <h6 class="name fw-semibold">{{ $review->name }}</h6>
                                                <ul class="list-star">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <li><i class="icon-star {{ $i <= $review->rating ? '' : 'text-main-4' }}"></i></li>
                                                    @endfor
                                                </ul>
                                            </div>
                                            <p class="text-review">{{ $review->comment }}</p>
                                            <p class="date-review body-small">{{ $review->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <p class="body-text-3 text-main-2 py-3">No reviews yet. Be the first to review this product!</p>
                                    </li>
                                @endforelse
                            </ul>

                            {{-- Add Review Form --}}
                            <div class="add-comment-wrap sticky-top w-100">
                                <h5 class="fw-semibold">Add your comment</h5>
                                <form class="form-add-comment" method="POST" action="{{ route('review.store', $product) }}">
                                    @csrf
                                    <fieldset class="rate">
                                        <label>Rating:</label>
                                        <ul class="list-star justify-content-start" id="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <li data-rating="{{ $i }}" style="cursor:pointer;" class="star-item">
                                                    <i class="icon-star text-main-4"></i>
                                                </li>
                                            @endfor
                                        </ul>
                                        <input type="hidden" name="rating" id="rating-value" value="">
                                        @error('rating')<p class="text-danger body-small">{{ $message }}</p>@enderror
                                    </fieldset>
                                    <fieldset>
                                        <label>Name:</label>
                                        <input type="text" name="name" placeholder="Your name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                                        @error('name')<p class="text-danger body-small">{{ $message }}</p>@enderror
                                    </fieldset>
                                    <fieldset>
                                        <label>Email:</label>
                                        <input type="email" name="email" placeholder="Your email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                        @error('email')<p class="text-danger body-small">{{ $message }}</p>@enderror
                                    </fieldset>
                                    <fieldset class="align-items-sm-start">
                                        <label>Comment:</label>
                                        <textarea name="text" placeholder="Write your review..." required>{{ old('text') }}</textarea>
                                        @error('text')<p class="text-danger body-small">{{ $message }}</p>@enderror
                                    </fieldset>
                                    <div class="btn-submit">
                                        <button type="submit" class="tf-btn btn-gray btn-large-2">
                                            <span class="text-white">Add Review</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /Product Description Tab -->

    {{-- Related Products --}}
    @if($related_products->count() > 0)
    <section class="tf-sp-4 bg-light">
        <div class="container">
            <h5 class="fw-semibold mb-4">Related Products</h5>
            <div class="swiper tf-grid-layout swiper-product" data-preview="2" data-tablet="3" data-mobile="2" data-space="20">
                <div class="swiper-wrapper">
                    @foreach($related_products as $rp)
                        <div class="swiper-slide">
                            @include('frontend.partials.product-item-deal', ['product' => $rp, 'showProgress' => false])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    @push('js')
        <script type="module" src="{{asset($filePath)}}/js/drift.min.js"></script>
        <script type="module" src="{{asset($filePath)}}/js/zoom.js"></script>
        <script>
            $(document).ready(function () {
                // ── Quantity +/- buttons ──────────────────────────────────────────
                const qtyInput = $('#product-qty');
                const maxQty  = parseInt(qtyInput.attr('max')) || 9999;

                $('#btn-increase').on('click', function () {
                    let val = parseInt(qtyInput.val()) || 1;
                    if (val < maxQty) qtyInput.val(val + 1);
                });
                $('#btn-decrease').on('click', function () {
                    let val = parseInt(qtyInput.val()) || 1;
                    if (val > 1) qtyInput.val(val - 1);
                });

                // ── Star rating picker ────────────────────────────────────────────
                const stars = $('#star-rating .star-item');
                stars.on('click', function () {
                    const rating = $(this).data('rating');
                    $('#rating-value').val(rating);
                    stars.each(function (i) {
                        $(this).find('i').toggleClass('text-main-4', i >= rating);
                    });
                });
                stars.on('mouseenter', function () {
                    const rating = $(this).data('rating');
                    stars.each(function (i) {
                        $(this).find('i').toggleClass('text-main-4', i >= rating);
                    });
                });
                $('#star-rating').on('mouseleave', function () {
                    const selected = parseInt($('#rating-value').val()) || 0;
                    stars.each(function (i) {
                        $(this).find('i').toggleClass('text-main-4', i >= selected);
                    });
                });

                // ── Wishlist button text update ───────────────────────────────────
                $(document).on('click', '#product-detail-wishlist', function () {
                    const btn = $(this);
                    // Defer text update after the AJAX in storefront.js fires
                    setTimeout(function () {
                        const isActive = btn.hasClass('active');
                        btn.find('span').text(isActive ? 'In Wishlist' : 'Wishlist');
                        btn.attr('title', isActive ? 'Remove from Wishlist' : 'Add to Wishlist');
                    }, 500);
                });
            });
        </script>
    @endpush

    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
