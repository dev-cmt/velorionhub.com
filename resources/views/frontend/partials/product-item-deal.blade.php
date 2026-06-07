@isset($product)
@php
    $isSale = floatval($product->sale_price) < floatval($product->regular_price);
    $discountPercentage = 0;
    if ($isSale && floatval($product->regular_price) > 0) {
        $discountPercentage = round(((floatval($product->regular_price) - floatval($product->sale_price)) / floatval($product->regular_price)) * 100);
    }

    $mainImage   = $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
    $hoverImage  = $product->hover_image ? asset($product->hover_image) : $mainImage;
    $productUrl  = route('product.show', $product->slug);
    $categoryName = $product->category->name ?? 'Uncategorized';
    $categoryUrl  = $product->category ? route('shop', ['category' => $product->category->slug]) : route('shop');
    $sold        = (int) ($product->stock_out ?? 0);
    $available   = max(0, (int) ($product->total_stock ?? 0));
    $totalUnits  = $sold + $available;
    $soldPercent = $totalUnits > 0 ? round(($sold / $totalUnits) * 100) : 0;

    $inWishlist  = false;
    $inCompare   = false;
    try {
        $sessionId   = (Auth::id() ?? session()->getId());
        $wishlistCart = \Cart::session($sessionId . '_wishlist');
        if ($wishlistCart && $wishlistCart->get($product->id)) { $inWishlist = true; }
        $compareCart  = \Cart::session($sessionId . '_compare');
        if ($compareCart && $compareCart->get($product->id))  { $inCompare  = true; }
    } catch (\Exception $e) {}
@endphp
<div class="card-product style-img-border wow fadeInUp animated" data-wow-delay="{{ $wowDelay ?? '0s' }}">
    <div class="card-product-wrapper">
        <a href="{{ $productUrl }}" class="product-img">
            <img class="img-product lazyload" src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $product->name }}">
            <img class="img-hover lazyload" src="{{ $hoverImage }}" data-src="{{ $hoverImage }}" alt="{{ $product->name }}">
        </a>
        @if($isSale)
            <div class="box-sale-wrap pst-default z-5">
                <p class="small-text">Sale</p>
                <p class="title-sidebar-2">{{ $discountPercentage }}%</p>
            </div>
        @endif

        <!--Action Buttons-->
        <ul class="list-product-btn {{ $btnClass ?? '' }}">
            <li>
                <a href="#shoppingCart" data-bs-toggle="offcanvas"
                    class="box-icon add-to-cart btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }}"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->sale_price }}"
                    data-image="{{ $mainImage }}"
                    data-url="{{ $productUrl }}">
                    <span class="icon icon-cart2"></span>
                    <span class="tooltip">Add to Cart</span>
                </a>
            </li>
            <li class="{{ $wishlistClass ?? 'd-none d-sm-block' }} wishlist">
                <a href="#;"
                    class="box-icon btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }} {{ $inWishlist ? 'active' : '' }}"
                    data-action="wishlist"
                    data-id="{{ $product->id }}">
                    <span class="icon icon-heart2"></span>
                    <span class="tooltip">{{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}</span>
                </a>
            </li>
            <li>
                <a href="#quickView" data-bs-toggle="modal"
                    class="box-icon quickview btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }}"
                    data-product-url="{{ $productUrl }}"
                    data-product-id="{{ $product->id }}">
                    <span class="icon icon-view"></span>
                    <span class="tooltip">Quick View</span>
                </a>
            </li>
            <li class="{{ $compareClass ?? 'd-none d-sm-block' }}">
                <a href="#compare" data-bs-toggle="offcanvas"
                    class="box-icon btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }} {{ $inCompare ? 'active' : '' }}"
                    data-action="compare"
                    data-id="{{ $product->id }}">
                    <span class="icon icon-compare1"></span>
                    <span class="tooltip">Compare</span>
                </a>
            </li>
        </ul>

    </div>
    <div class="card-product-info">
        <div class="box-title">
            <div class="d-flex flex-column">
                <p class="caption text-main-2 font-2">
                    <a href="{{ $categoryUrl }}" class="link text-main-2">{{ $categoryName }}</a>
                </p>
                <a href="{{ $productUrl }}" class="name-product body-md-2 fw-semibold text-secondary link">
                    {{ $product->name }}
                </a>
            </div>
            <p class="price-wrap fw-medium">
                <span class="new-price price-text fw-medium text-primary mb-0">TK {{ number_format($product->sale_price, 2) }}</span>
                @if($isSale)
                    <span class="old-price body-md-2 text-main-2 fw-normal">TK {{ number_format($product->regular_price, 2) }}</span>
                @endif
            </p>
        </div>
        @if($showProgress ?? false)
            <div class="box-infor-detail">
                <div class="product-progress-sale">
                    <div class="progress-sold progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: {{ $soldPercent }}%"></div>
                    </div>
                    <div class="box-quantity d-flex justify-content-between">
                        <p class="text-avaiable caption">
                            Sold: <span class="fw-bold">{{ $sold }}</span>
                        </p>
                        <p class="text-avaiable caption">
                            Available: <span class="fw-bold">{{ $available }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endisset
