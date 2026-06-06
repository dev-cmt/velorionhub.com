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
<div class="card-product style-img-border {{ $cardClass ?? '' }}" data-wow-delay="{{ $wowDelay ?? '0s' }}">
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
        @include('frontend.partials.product-actions')
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
