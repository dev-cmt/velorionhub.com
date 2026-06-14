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

    $brandSlug = $product->brand->slug ?? '';
    $brandName = $product->brand->name ?? '';

    $rating = round($product->reviews_avg_rating ?? 0);
    $ratingStr = $rating . ' Star';

    $inWishlist  = false;
    $inCompare   = false;
    $inCart      = false;
    $cartItemId  = null;
    try {
        $sessionId   = (Auth::id() ?? session()->getId());
        $wishlistCart = \Cart::session($sessionId . '_wishlist');
        if ($wishlistCart && $wishlistCart->get($product->id)) { $inWishlist = true; }
        $compareCart  = \Cart::session($sessionId . '_compare');
        if ($compareCart && $compareCart->get($product->id))  { $inCompare  = true; }

        $cartItems = \Cart::session($sessionId)->getContent();
        foreach ($cartItems as $item) {
            if (($item->attributes->product_id ?? null) == $product->id) {
                $inCart = true;
                $cartItemId = $item->id;
                break;
            }
        }
    } catch (\Exception $e) {}
@endphp

<div class="card-product style-img-border wow fadeInUp animated" data-wow-delay="{{ $wowDelay ?? '0s' }}"
    data-condition="New"
    data-brand="{{ $brandSlug }}"
    data-deal="Normal"
    data-rate="{{ $ratingStr }}">

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
        <ul class="list-product-btn">
            <li>
                <a href="#;"
                    class="box-icon add-to-cart btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }} {{ $inCart ? 'in-cart active' : '' }}"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->sale_price }}"
                    data-image="{{ $mainImage }}"
                    data-url="{{ $productUrl }}"
                    data-has-variant="{{ $product->has_variant ? '1' : '0' }}"
                    data-product-url="{{ $productUrl }}"
                    @if($inCart) data-cart-id="{{ $cartItemId }}" @endif>
                    <span class="icon icon-cart2"></span>
                    <span class="tooltip">{{ $inCart ? 'In Cart' : 'Add to Cart' }}</span>
                </a>
            </li>
            <li class="d-none d-sm-block wishlist">
                <a href="#;"
                    class="box-icon btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }} {{ $inWishlist ? 'active' : '' }}"
                    data-action="wishlist"
                    data-id="{{ $product->id }}">
                    <span class="icon icon-heart2"></span>
                    <span class="tooltip">{{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}</span>
                </a>
            </li>
            {{-- <li>
                <a href="#quickView" data-bs-toggle="modal"
                    class="box-icon quickview btn-icon-action hover-tooltip {{ $tooltipClass ?? 'tooltip-left' }}"
                    data-product-url="{{ $productUrl }}"
                    data-product-id="{{ $product->id }}">
                    <span class="icon icon-view"></span>
                    <span class="tooltip">Quick View</span>
                </a>
            </li> --}}
            <li class="d-none d-sm-block compare">
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
                <span class="new-price price-text fw-medium text-primary mb-0 cur-price" data-price="{{ $product->sale_price }}">
                    TK {{ number_format($product->sale_price, 2) }}
                </span>

                @if($isSale)
                    <span class="old-price body-md-2 text-main-2 fw-normal cur-price" data-price="{{ $product->regular_price }}">
                        TK {{ number_format($product->regular_price, 2) }}
                    </span>
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

        @if($showDetail ?? false)
            <div class="box-infor-detail">
                @if(is_array($product->specification))
                    {{-- <ul class="list-computer-memory">
                        @foreach(array_slice($product->specification, 0, 2) as $key => $val)
                            <li>
                                <p class="caption">{{ $key }}: {{ $val }}</p>
                            </li>
                        @endforeach
                    </ul> --}}
                    <ul class="list-infor-fearture">
                        @foreach(array_slice($product->specification, 0, 2) as $key => $val)
                            <li>
                                <p class="caption name-feature">{{ $key }}: </p>
                                <p class="caption property"> {{ $val }} </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="star-review flex-wrap">
                    <ul class="list-star">
                        @for($i = 1; $i <= 5; $i++)
                            <li><i class="icon-star {{ $i <= $rating ? '' : 'text-main-4' }}"></i></li>
                        @endfor
                    </ul>
                    <p class="caption text-main-2">({{ $product->reviews_count ?? 0 }})</p>
                </div>
                <a href="#compare" data-bs-toggle="offcanvas" class="tf-btn-icon style-2"
                    data-action="compare"
                    data-id="{{ $product->id }}">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 6.5V9V6.5ZM9 9V11.5V9ZM9 9H11.5H9ZM9 9H6.5H9ZM16.5 9C16.5 9.98491 16.306 10.9602 15.9291 11.8701C15.5522 12.7801 14.9997 13.6069 14.3033 14.3033C13.6069 14.9997 12.7801 15.5522 11.8701 15.9291C10.9602 16.306 9.98491 16.5 9 16.5C8.01509 16.5 7.03982 16.306 6.12987 15.9291C5.21993 15.5522 4.39314 14.9997 3.6967 14.3033C3.00026 13.6069 2.44781 12.7801 2.0709 11.8701C1.69399 10.9602 1.5 9.98491 1.5 9C1.5 7.01088 2.29018 5.10322 3.6967 3.6967C5.10322 2.29018 7.01088 1.5 9 1.5C10.9891 1.5 12.8968 2.29018 14.3033 3.6967C15.7098 5.10322 16.5 7.01088 16.5 9Z"
                            stroke="#004EC3" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="body-text-3 fw-normal">Compare</span>
                </a>
            </div>
        @endif
    </div>

    @if($showActionBtn ?? false)
        <div class="card-product-btn">
            <a href="#shoppingCart" data-bs-toggle="offcanvas"
                class="tf-btn btn-line w-100 btn-cart add-to-cart {{ $inCart ? 'in-cart active' : '' }}"
                data-id="{{ $product->id }}"
                data-name="{{ $product->name }}"
                data-price="{{ $product->sale_price }}"
                data-image="{{ $mainImage }}"
                data-url="{{ $productUrl }}"
                data-has-variant="{{ $product->has_variant ? '1' : '0' }}"
                data-product-url="{{ $productUrl }}"
                @if($inCart) data-cart-id="{{ $cartItemId }}" @endif>
                <span>{{ $inCart ? 'Remove from Cart' : 'Add to cart' }}</span>
                <i class="{{ $inCart ? 'icon-close' : 'icon-cart-2' }}"></i>
            </a>
            <div class="box-btn">
                <a href="#compare" data-bs-toggle="offcanvas"
                    class="tf-btn-icon style-2 type-black"
                    data-action="compare"
                    data-id="{{ $product->id }}">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 6.5V9V6.5ZM9 9V11.5V9ZM9 9H11.5H9ZM9 9H6.5H9ZM16.5 9C16.5 9.98491 16.306 10.9602 15.9291 11.8701C15.5522 12.7801 14.9997 13.6069 14.3033 14.3033C13.6069 14.9997 12.7801 15.5522 11.8701 15.9291C10.9602 16.306 9.98491 16.5 9 16.5C8.01509 16.5 7.03982 16.306 6.12987 15.9291C5.21993 15.5522 4.39314 14.9997 3.6967 14.3033C3.00026 13.6069 2.44781 12.7801 2.0709 11.8701C1.69399 10.9602 1.5 9.98491 1.5 9C1.5 7.01088 2.29018 5.10322 3.6967 3.6967C5.10322 2.29018 7.01088 1.5 9 1.5C10.9891 1.5 12.8968 2.29018 14.3033 3.6967C15.7098 5.10322 16.5 7.01088 16.5 9Z"
                            stroke="#004EC3" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    <span class="body-text-3 fw-normal">Compare</span>
                </a>
                <a href="#;"
                    class="tf-btn-icon style-2 type-black"
                    data-action="wishlist"
                    data-id="{{ $product->id }}">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.59837 5.26487C3.25014 5.61309 2.97391 6.02649 2.78546 6.48146C2.597 6.93644 2.5 7.42408 2.5 7.91654C2.5 8.409 2.597 8.89664 2.78546 9.35161C2.97391 9.80658 3.25014 10.22 3.59837 10.5682L10 16.9699L16.4017 10.5682C17.105 9.86494 17.5001 8.9111 17.5001 7.91654C17.5001 6.92197 17.105 5.96814 16.4017 5.26487C15.6984 4.5616 14.7446 4.16651 13.75 4.16651C12.7555 4.16651 11.8016 4.5616 11.0984 5.26487L10 6.3632L8.9017 5.26487C8.55348 4.91665 8.14008 4.64042 7.68511 4.45196C7.23013 4.2635 6.74249 4.1665 6.25003 4.1665C5.75757 4.1665 5.26993 4.2635 4.81496 4.45196C4.35998 4.64042 3.94659 4.91665 3.59837 5.26487V5.26487Z"
                            stroke="#FF3D3D" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    <span class="body-text-3 fw-normal">Wishlist</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endisset
