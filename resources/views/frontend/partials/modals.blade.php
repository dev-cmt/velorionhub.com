@php
    $cartSessionKey = Auth::id() ?? session()->getId();
    $cart = Darryldecode\Cart\Facades\CartFacade::session($cartSessionKey);
    $cartItems = $cart->getContent();
    $cartSubtotal = $cart->getSubTotal();

    $compareSessionKey = $cartSessionKey . '_compare';
    $compareItems = Darryldecode\Cart\Facades\CartFacade::session($compareSessionKey)->getContent();
@endphp

<!-- Product Compare -->
<div class="offcanvas offcanvas-bottom offcanvas-compare" id="compare">
    <div class="offcanvas-content">
        <div class="header">
            <span class="icon-close icon-close-popup link" data-bs-dismiss="offcanvas"></span>
        </div>
        <div class="wrap">
            <div class="container">
                <div class="tf-compare-list">
                    <h5 class="title fw-semibold">
                        Compare <br class="d-none d-md-block">
                        Products
                    </h5>
                    <div class="mini-compare-empty w-100 text-center" style="{{ $compareItems->isEmpty() ? '' : 'display: none;' }}">
                        <h6>
                            Your compare is curently empty
                        </h6>
                    </div>
                    <div class="tf-compare-wrap" style="{{ $compareItems->isEmpty() ? 'display: none;' : '' }}">
                        @foreach($compareItems as $item)
                            @php
                                $image = ($item->attributes && isset($item->attributes['image'])) ? $item->attributes['image'] : asset('images/no-image.jpg');
                                $url = ($item->attributes && isset($item->attributes['url'])) ? $item->attributes['url'] : '#';
                            @endphp
                            <div class="tf-compare-item" data-id="{{ $item->id }}">
                                <span class="icon-close remove" data-compare-id="{{ $item->id }}"></span>
                                <a href="{{ $url }}" class="image">
                                    <img class="lazyload" src="{{ $image }}"
                                        data-src="{{ $image }}" alt="{{ $item->name }}">
                                </a>
                                <div class="content">
                                    <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                        href="{{ $url }}">
                                        {{ $item->name }}
                                    </a>
                                    <p class="price-wrap fw-medium">
                                        <span class="new-price price-text fw-medium">TK {{ number_format($item->price, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tf-compare-buttons">
                        <div class="tf-compare-btn">
                            <a href="{{ route('compare') }}" class="tf-btn btn-gray btn-large-3">
                                <span class="text-white">Compare Products</span>
                            </a>
                            <div class="tf-btn tf-compapre-button-clear-all clear-file-delete link btn-large-3">
                                <span class="text-white">Clear All Products</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Product Compare -->

<!-- Shoping Cart -->
<div class="offcanvas offcanvas-end popup-style popup-shopping-cart" id="shoppingCart">
    <div class="popup-wrapper">
        <div class="popup-header">
            <h5 class="title fw-semibold">Shopping cart</h5>
            <span class="icon-close icon-close-popup link" data-bs-dismiss="offcanvas"></span>
        </div>
        <div class="minicart-empty text-center" style="{{ $cartItems->isEmpty() ? '' : 'display: none;' }}">
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M80.6344 72.6641H33.3641C32.8541 72.6646 32.3525 72.5345 31.907 72.2864C31.4615 72.0383 31.0869 71.6803 30.8188 71.2465C30.5507 70.8127 30.398 70.3176 30.3753 69.8081C30.3526 69.2987 30.4606 68.7919 30.6891 68.336L33.4656 62.7844C33.6401 62.4347 33.678 62.0325 33.5719 61.6563L22.0563 21.361C21.7786 20.4019 21.1977 19.5587 20.4005 18.9575C19.6033 18.3564 18.6328 18.0298 17.6344 18.0266H7.78282C7.36822 18.0266 6.97059 18.1913 6.67742 18.4845C6.38425 18.7777 6.21954 19.1753 6.21954 19.5899C6.21954 20.0045 6.38425 20.4021 6.67742 20.6953C6.97059 20.9885 7.36822 21.1532 7.78282 21.1532H17.6359C17.9554 21.1542 18.2658 21.2587 18.5208 21.4511C18.7758 21.6436 18.9615 21.9135 19.05 22.2204L30.3984 61.9313L27.8922 66.9391C27.4257 67.8717 27.2054 68.9081 27.2523 69.9497C27.2991 70.9914 27.6115 72.0038 28.1598 72.8908C28.7081 73.7777 29.4741 74.5098 30.3849 75.0173C31.2958 75.5249 32.3213 75.7911 33.3641 75.7907H80.6344C81.0488 75.7907 81.4462 75.6261 81.7392 75.333C82.0323 75.04 82.1969 74.6426 82.1969 74.2282C82.1969 73.8138 82.0323 73.4163 81.7392 73.1233C81.4462 72.8303 81.0488 72.6641 80.6344 72.6641Z"
                    fill="#73787D"></path>
                <path
                    d="M93.175 25.3828C92.8884 24.9852 92.5114 24.6615 92.0751 24.4382C91.6388 24.2149 91.1557 24.0984 90.6656 24.0984H27.7266C27.3122 24.0984 26.9147 24.263 26.6217 24.556C26.3287 24.8491 26.1641 25.2465 26.1641 25.6609C26.1641 26.0753 26.3287 26.4727 26.6217 26.7657C26.9147 27.0588 27.3122 27.2234 27.7266 27.2234L90.625 27.1718L85.5781 42.3125H32.9312C32.5168 42.3125 32.1194 42.4771 31.8264 42.7701C31.5334 43.0631 31.3687 43.4606 31.3687 43.875C31.3687 44.2894 31.5334 44.6868 31.8264 44.9798C32.1194 45.2728 32.5168 45.4375 32.9312 45.4375H84.5359L79.5078 60.5234H38.1375C37.7229 60.5234 37.3253 60.6881 37.0321 60.9813C36.7389 61.2744 36.5742 61.6721 36.5742 62.0867C36.5742 62.5013 36.7389 62.8989 37.0321 63.1921C37.3253 63.4852 37.7229 63.6499 38.1375 63.6499H80.6344C80.9624 63.65 81.2822 63.5468 81.5484 63.355C81.8145 63.1632 82.0135 62.8925 82.1172 62.5812L93.5875 28.1671C93.7438 27.7037 93.7879 27.2099 93.7162 26.7261C93.6445 26.2423 93.459 25.7809 93.175 25.3828ZM32.0672 78.7343C21.9781 79.0562 21.9797 93.6843 32.0672 94.0031C42.1562 93.6828 42.1531 79.0515 32.0672 78.7343ZM32.0672 90.8765C30.8716 90.8765 29.7251 90.4016 28.8797 89.5562C28.0343 88.7108 27.5594 87.5642 27.5594 86.3687C27.5594 85.1732 28.0343 84.0266 28.8797 83.1812C29.7251 82.3358 30.8716 81.8609 32.0672 81.8609C33.2627 81.8609 34.4093 82.3358 35.2547 83.1812C36.1001 84.0266 36.575 85.1732 36.575 86.3687C36.575 87.5642 36.1001 88.7108 35.2547 89.5562C34.4093 90.4016 33.2627 90.8765 32.0672 90.8765ZM74.5625 78.7343C64.4734 79.0546 64.475 93.6843 74.5625 94.0031C84.6531 93.6828 84.65 79.0531 74.5625 78.7343ZM74.5625 90.8765C73.367 90.8765 72.2204 90.4016 71.375 89.5562C70.5296 88.7108 70.0547 87.5642 70.0547 86.3687C70.0547 85.1732 70.5296 84.0266 71.375 83.1812C72.2204 82.3358 73.367 81.8609 74.5625 81.8609C75.758 81.8609 76.9046 82.3358 77.75 83.1812C78.5954 84.0266 79.0703 85.1732 79.0703 86.3687C79.0703 87.5642 78.5954 88.7108 77.75 89.5562C76.9046 90.4016 75.758 90.8765 74.5625 90.8765Z"
                    fill="#73787D"></path>
                <path
                    d="M57.8016 15.375C58.216 15.375 58.6134 15.2103 58.9064 14.9173C59.1995 14.6243 59.3641 14.2269 59.3641 13.8125V7.55933C59.3641 7.14492 59.1995 6.7475 58.9064 6.45447C58.6134 6.16145 58.216 5.99683 57.8016 5.99683C57.3872 5.99683 56.9897 6.16145 56.6967 6.45447C56.4037 6.7475 56.2391 7.14492 56.2391 7.55933V13.8125C56.2391 14.2269 56.4037 14.6243 56.6967 14.9173C56.9897 15.2103 57.3872 15.375 57.8016 15.375ZM43.4328 20.4109C43.578 20.5561 43.7503 20.6712 43.94 20.7498C44.1297 20.8284 44.333 20.8688 44.5383 20.8688C44.7436 20.8688 44.9469 20.8284 45.1366 20.7498C45.3262 20.6712 45.4986 20.5561 45.4986 20.4109L41.2219 13.7796C40.9287 13.4867 40.5311 13.3221 40.1166 13.3223C39.7022 13.3224 39.3047 13.4872 39.0117 13.7804C38.7188 14.0736 38.5542 14.4712 38.5544 14.8857C38.5545 15.3001 38.7193 15.6976 39.0125 15.9906L43.4328 20.4109ZM71.0656 20.8687C71.2708 20.8689 71.4741 20.8286 71.6637 20.75C71.8532 20.6714 72.0254 20.5562 72.1703 20.4109L76.5922 15.989C76.8852 15.6958 77.0497 15.2983 77.0495 14.8838C77.0494 14.4693 76.8846 14.0718 76.5914 13.7789C76.2982 13.4859 75.9007 13.3214 75.4862 13.3215C75.0717 13.3217 74.6742 13.4865 74.3813 13.7796L69.9594 18.2015C69.746 18.4221 69.6018 18.7002 69.5445 19.0017C69.4872 19.3032 69.5194 19.6148 69.6369 19.8983C69.7545 20.1817 69.9524 20.4246 70.2062 20.597C70.4601 20.7695 70.7588 20.8639 71.0656 20.8687Z"
                    fill="#73787D"></path>
            </svg>
            <h6>
                Your cart is curently empty <br>
                Let up help you find the perfect item
            </h6>
            <a href="{{ route('shop') }}" class="tf-btn btn-gray w-100">
                <span class="text-white">Show All Shop</span>
            </a>
        </div>
        <ul class="popup-body product-list-wrap" style="{{ $cartItems->isEmpty() ? 'display: none;' : '' }}">
            @foreach($cartItems as $item)
                @php
                    $associatedModel = $item->associatedModel;
                    $image = ($associatedModel && $associatedModel->main_image)
                        ? asset($associatedModel->main_image)
                        : (($item->attributes && isset($item->attributes['image'])) ? $item->attributes['image'] : asset('images/no-image.jpg'));
                    $url = ($associatedModel)
                        ? route('product.show', $associatedModel->slug)
                        : (($item->attributes && isset($item->attributes['url'])) ? $item->attributes['url'] : '#');
                @endphp
                <li class="file-delete" data-cart-id="{{ $item->id }}">
                    <div class="card-product style-row row-small-2 align-items-center">
                        <div class="card-product-wrapper">
                            <a href="{{ $url }}" class="product-img">
                                <img class="img-product lazyload" src="{{ $image }}"
                                    data-src="{{ $image }}" alt="{{ $item->name }}">
                            </a>
                        </div>
                        <div class="card-product-info">
                            <div class="box-title">
                                <a href="{{ $url }}"
                                    class="name-product body-md-2 fw-semibold text-secondary link">
                                    {{ $item->name }}
                                </a>
                                @if(!empty($item->attributes['variant_label']))
                                    <span class="variant-label text-muted d-block small">{{ $item->attributes['variant_label'] }}</span>
                                @endif
                                <p class="price-wrap fw-medium">
                                    <span class="new-price price-text fw-medium">TK {{ number_format($item->price, 2) }}</span>
                                </p>
                                <p class="body-md-2">X{{ $item->quantity }}</p>
                            </div>
                        </div>
                        <span class="icon-close remove link" data-cart-id="{{ $item->id }}"></span>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="popup-footer" style="{{ $cartItems->isEmpty() ? 'display: none;' : '' }}">
            <p class="cart-total fw-semibold">
                <span>Subtotal:</span>
                <span class="price-amount product-title text-primary">TK {{ number_format($cartSubtotal, 2) }}</span>
            </p>
            <div class="box-btn">
                <a href="{{ route('cart') }}" class="tf-btn btn-gray">
                    <span class="text-white">View Cart</span>
                </a>
                <a href="{{ route('checkout') }}" class="tf-btn"><span class="text-white">Check Out</span></a>
            </div>
            <div class="delivery-progress">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: 80%;"></div>
                </div>
                <p class="body-text-3">
                    <i class="icon-delivery-2 fs-24"></i>
                    Free shipping on all orders over <span class="fw-bold">$250</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- /Shoping Cart -->

<!-- Quick View -->
<div class="modal fade modalCentered modal-def modal-quick-view" id="quickView">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content flex-md-row">
            <span class="icon-close icon-close-popup link" data-bs-dismiss="modal"></span>
            <div class="quickview-image">
                <div class="product-thumb-slider">
                    <div class="swiper tf-product-view-main">
                        <div class="swiper-wrapper">
                            <!-- item 1 -->
                            <div class="swiper-slide">
                                <a href="product-detail.html" class="d-block tf-image-view">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-1.jpg"
                                        data-src="{{asset($filePath)}}/images/product/product-thumb/quickview-1.jpg" alt=""
                                        class="lazyload">
                                </a>
                            </div>
                            <!-- item 2 -->
                            <div class="swiper-slide">
                                <a href="product-detail.html" class="d-block tf-image-view">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-2.jpg"
                                        data-src="{{asset($filePath)}}/images/product/product-thumb/quickview-2.jpg" alt=""
                                        class="lazyload">
                                </a>
                            </div>
                            <!-- item 3 -->
                            <div class="swiper-slide">
                                <a href="product-detail.html" class="d-block tf-image-view">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-3.jpg"
                                        data-src="{{asset($filePath)}}/images/product/product-thumb/quickview-3.jpg" alt=""
                                        class="lazyload">
                                </a>
                            </div>
                            <!-- item 4 -->
                            <div class="swiper-slide">
                                <a href="product-detail.html" class="d-block tf-image-view">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-4.jpg"
                                        data-src="{{asset($filePath)}}/images/product/product-thumb/quickview-4.jpg" alt=""
                                        class="lazyload">
                                </a>
                            </div>
                            <!-- item 5 -->
                            <div class="swiper-slide">
                                <a href="product-detail.html" class="d-block tf-image-view">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-5.jpg"
                                        data-src="{{asset($filePath)}}/images/product/product-thumb/quickview-5.jpg" alt=""
                                        class="lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="swiper-button-prev nav-swiper-2 single-slide-prev"></div>
                        <div class="swiper-button-next nav-swiper-2 single-slide-next"></div>
                    </div>
                    <div class="swiper tf-product-view-thumbs" data-direction="horizontal">
                        <div class="swiper-wrapper">
                            <!-- item 1 -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-1.jpg" alt="">
                                </div>
                            </div>
                            <!-- item 2 -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-2.jpg" alt="">
                                </div>
                            </div>
                            <!-- item 3 -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-3.jpg" alt="">
                                </div>
                            </div>
                            <!-- item 4 -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-4.jpg" alt="">
                                </div>
                            </div>
                            <!-- item 5 -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="{{asset($filePath)}}/images/product/product-thumb/quickview-5.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="quickview-info-wrap">
                <div class="quickview-info-inner">
                    <div class="tf-product-info-content">
                        <div class="infor-heading">
                            <p class="caption">Categories:
                                <a href="shop-default.html" class="link text-secondary">
                                    Consumer Electronics
                                </a>
                            </p>
                            <h5 class="product-info-name fw-semibold">
                                <a href="product-detail.html" class="link">
                                    Elite Gourmet EKT1001B Electric
                                    BPA-Free Glass Kettle, Cordless 360° Base
                                </a>
                            </h5>
                            <ul class="product-info-rate-wrap">
                                <li class="star-review">
                                    <ul class="list-star">
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star text-main-4"></i>
                                        </li>
                                    </ul>
                                    <p class="caption text-main-2">Reviews (1.738)</p>
                                </li>
                                <li>
                                    <p class="caption text-main-2">Sold: 349</p>
                                </li>
                                <li>
                                    <a href="shop-default.html" class="caption text-secondary link">View shop</a>
                                </li>
                            </ul>
                        </div>
                        <div class="infor-center">
                            <div class="product-info-price">
                                <h4 class="text-primary">$18.99</h4>
                                <span class="price-text text-main-2 old-price">$20.99</span>
                            </div>
                            <ul class="product-fearture-list">
                                <li>
                                    <p class="body-md-2 fw-semibold">Brand</p>
                                    <span class="body-text-3">Elite Gourmet</span>
                                </li>
                                <li>
                                    <p class="body-md-2 fw-semibold">Capacity</p>
                                    <span class="body-text-3">1 Liters</span>
                                </li>
                                <li>
                                    <p class="body-md-2 fw-semibold">Material</p>
                                    <span class="body-text-3">Glass</span>
                                </li>
                                <li>
                                    <p class="body-md-2 fw-semibold">Wattage</p>
                                    <span class="body-text-3">1100 watts</span>
                                </li>

                            </ul>
                        </div>
                        <div class="infor-bottom">
                            <h6 class="fw-semibold">About this item</h6>
                            <ul class="product-about-list">
                                <li>
                                    <p class="body-text-3">
                                        Here’s the quickest way to enjoy your delicious hot tea
                                        every
                                        single day.
                                    </p>
                                </li>
                                <li>
                                    <p class="body-text-3">
                                        100% BPA - Free premium design meets excellent
                                    </p>
                                </li>
                                <li>
                                    <p class="body-text-3">
                                        So easy convenient that everyone can use it
                                    </p>
                                </li>
                                <li>
                                    <p class="body-text-3">
                                        This powerful 900-1100-Watt kettle has convenient capacity
                                        markings on the body lets you accurately
                                    </p>
                                </li>
                                <li>
                                    <p class="body-text-3">
                                        1 year limited warranty and us-based customer support team
                                        lets
                                        you buy with confidence.
                                    </p>
                                </li>
                                <li>
                                    <p class="body-text-3">
                                        It’s very thin—7.45mm / 0.29″—and starts at just 465g / 1.03lbs, thanks to
                                        an aluminum chassis that’s both lightweight and durable.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="quickview-variants-container mb-3" style="display:none;">
                        <style>
                            .quickview-variants-container .product-form__item { margin-bottom: 12px; }
                            .quickview-variants-container .product-form__title { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
                            .quickview-variants-container .variant-swatch-item { position: relative; user-select: none; }
                            .quickview-variants-container .variant-swatch-label {
                                display: inline-flex; align-items: center; justify-content: center;
                                min-width: 40px; height: 40px; padding: 4px;
                                border: 1.5px solid #ddd; border-radius: 4px; cursor: pointer;
                                transition: all 0.2s ease-in-out; background: #fff; margin-bottom: 0; overflow: hidden;
                            }
                            .quickview-variants-container .variant-swatch-label:hover { border-color: #004EC3; }
                            .quickview-variants-container .variant-option:checked + .variant-swatch-label { border-color: #004EC3; background: #f0f7ff; font-weight: 600; }
                            .quickview-variants-container .swatch-image { width: 34px; height: 34px; object-fit: cover; border-radius: 2px; }
                            .quickview-variants-container .swatch-text { font-size: 12px; color: #333; padding: 0 8px; white-space: nowrap; }
                            .quickview-variants-container .variant-option:checked + .variant-swatch-label .swatch-text { color: #004EC3; }
                        </style>
                    </div>

                    <div class="box-quantity-wrap">
                        <div class="wg-quantity">
                            <span class="btn-quantity minus-btn">
                                <i class="icon-minus"></i>
                            </span>
                            <input class="quantity-product" type="text" name="number" value="1" min="1">
                            <span class="btn-quantity plus-btn">
                                <i class="icon-plus"></i>
                            </span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap" style="flex: 1;">
                            <a href="#shoppingCart" class="tf-btn btn-gray btn-add-quickview flex-grow-1" data-bs-toggle="offcanvas">
                                <span class="text-white">Add To Cart</span>
                            </a>
                            <button type="button" class="tf-btn btn-add-quickview-buynow flex-grow-1" style="background: #004EC3;">
                                <span class="text-white">Buy Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Quick View -->



<!-- Newsletter -->
{{-- <div class="modal modalCentered fade auto-popup modal-def modal-newleter" aria-modal="false" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <span class="icon icon-close icon-close-popup link" data-bs-dismiss="modal"></span>
            <div class="heading">
                <h5 class="fw-semibold">Join our newsletter for $10 offs</h5>
                <p class="body-md-2">Register now to get latest updates on promotions &amp; coupons. <br>
                    Don’t worry, we not spam!</p>
            </div>
            <div class="sib-form">
                <div id="sib-form-container" class="sib-form-container">
                    <div id="error-message" class="sib-form-message-panel">
                        <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                            <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z"></path>
                            </svg>
                            <span class="sib-form-message-panel__inner-text">
                                Your subscription could not be saved. Please try again.
                            </span>
                        </div>
                    </div>
                    <div id="success-message" class="sib-form-message-panel">
                        <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                            <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z"></path>
                            </svg>
                            <span class="sib-form-message-panel__inner-text">
                                Your subscription has been successful.
                            </span>
                        </div>
                    </div>
                    <div id="sib-container" class="sib-container--large sib-container--vertical">
                        <form id="sib-form" method="POST" action="https://3c02c1a1.sibforms.com/serve/MUIFABp-6TRH_ZaK3WSmgGEDN5JKtMVuO6AlfuFlAQ5zTnRMTM9BUGeezu_2xii-Q69nQvGvpnGbcxXU67nGQ5uDYgSnl0-UsYaPkTQENX9KjaNOrfvCz8rpEWGmf8Hp7zP5oS_WBIdftR5tby8mzBWr8pGQ7408FpU45UnQz5_Noqye8awDqyH6FqstWhwflIgsFbsd_AHEpMrk" data-type="subscription" novalidate="true">
                            <div style="display: none;">
                                <div class="sib-form-block">
                                    <p></p>
                                </div>
                            </div>
                            <div style="display: none;">
                                <div class="sib-form-block">
                                    <div class="sib-text-form-block">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div style="display: none;">
                                <div class="sib-optin sib-form-block">
                                    <div class="form__entry entry_mcq">
                                        <div class="form__label-row ">
                                            <div class="entry__choice">
                                                <label>
                                                    <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN">
                                                    <span class="checkbox checkbox_tick_positive"></span>
                                                    <span>
                                                        <p></p>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <label class="entry__error entry__error--primary">
                                        </label>
                                        <label class="entry__specification">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="sib-input sib-form-block">
                                    <div class="form__entry entry_block">
                                        <div class="form__label-row ">
                                            <label class="entry__label" for="EMAIL">
                                            </label>
                                            <div class="entry__field">
                                                <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="Enter Your Email Address" data-required="true" required="">
                                            </div>
                                        </div>
                                        <label class="entry__error entry__error--primary"></label>
                                        <label class="entry__specification">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="sib-form-block">
                                    <button class="sib-form-block__button sib-form-block__button-with-loader tf-btn w-100" form="sib-form" type="submit">
                                        <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                                            <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z"></path>
                                        </svg>
                                        <span class="text-white">Subscribe</span>
                                    </button>
                                </div>
                            </div>
                            <input type="text" name="email_address_check" value="" class="input--hidden" aria-hidden="true">
                            <input type="hidden" name="locale" value="en">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- /Newsletter -->

<!-- Mobile Menu -->
<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close btn-close-mb link" data-bs-dismiss="offcanvas"></span>
    <div class="logo-site">
        <a href="{{ url('/') }}">
            <img src="{{asset($filePath)}}/images/logo/logo.svg" alt="">
        </a>
    </div>
    <div class="mb-canvas-content">
        <div class="mb-body">
            <div class="flat-animate-tab">
                <div class="flat-title-tab-nav-mobile">
                    <ul class="menu-tab-line" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#main-menu" class="tab-link link fw-semibold active"
                                data-bs-toggle="tab">Menu</a>
                        </li>
                        <li class="br-line type-vertical bg-line h23"></li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#category" class="tab-link link fw-semibold"
                                data-bs-toggle="tab">Categories</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active show" id="main-menu" role="tabpanel">
                        <div class="mb-content-top">
                            <form class="form-search" action="{{ route('shop') }}" method="GET">
                                <fieldset>
                                    <input class="" type="text" placeholder="Search for anything" name="search"
                                        tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                                </fieldset>
                                <button type="submit" class="button-submit">
                                    <i class="icon-search"></i>
                                </button>
                            </form>
                            <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                                <li class="nav-mb-item">
                                    <a href="{{ route('home') }}" class="mb-menu-link">
                                        <span>Home</span>
                                    </a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="{{ route('shop') }}" class="mb-menu-link">
                                        <span>Shop</span>
                                    </a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="{{ route('about.us') }}" class="mb-menu-link">
                                        <span>About Us</span>
                                    </a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="{{ route('blog') }}" class="mb-menu-link">
                                        <span>Blog</span>
                                    </a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="{{ route('contacts') }}" class="mb-menu-link">
                                        <span>Contact Us</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="mb-other-content">
                            <ul class="mb-info">
                                <li>
                                    <p>
                                        Address:
                                        <span class="fw-medium">{{ $settings->address ?? '8500 Lorem Street Chicago, IL 55030 Dolor' }}</span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Phone:
                                        <a href="tel:{{ $settings->phone ?? '+8801XXXXXXXXX' }}">
                                            <span class="fw-medium">{{ $settings->phone ?? '+8801XXXXXXXXX' }}</span>
                                        </a>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Email:
                                        <a href="mailto:{{ $settings->email ?? 'onsus@support.com' }}">
                                            <span class="fw-medium">{{ $settings->email ?? 'onsus@support.com' }}</span>
                                        </a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane" id="category" role="tabpanel">
                        <div class="mb-content-top">
                            <ul class="nav-ul-mb">
                                <li class="nav-mb-item">
                                    <a href="#drd-categories-appearl" class="collapsed mb-menu-link"
                                        data-bs-toggle="collapse" aria-expanded="true"
                                        aria-controls="drd-categories-appearl">
                                        <span>Apparel</span>
                                        <span class="btn-open-sub"></span>
                                    </a>
                                    <div id="drd-categories-appearl" class="collapse">
                                        <ul class="sub-nav-menu">
                                            <li><a href="#" class="sub-nav-link">New arrival</a></li>
                                            <li><a href="#" class="sub-nav-link">Steall the deals</a></li>
                                            <li><a href="#" class="sub-nav-link">Best Sellers</a></li>
                                            <li><a href="#" class="sub-nav-link">Men</a></li>
                                            <li><a href="#" class="sub-nav-link">Woman</a></li>
                                            <li><a href="#" class="sub-nav-link">Season collection</a></li>
                                            <li><a href="#" class="sub-nav-link">This Week's Highlights</a></li>
                                            <li><a href="#" class="sub-nav-link">Home wear</a></li>
                                            <li><a href="#" class="sub-nav-link">Underwear</a></li>
                                            <li><a href="#" class="sub-nav-link">Travel clothes</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Automotive Parts</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Beauty & Personal Care</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Consumer Electronics</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Furniture</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Home Products</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Machinery</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Timepieces, Jewelry & Eyewear</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Tool & Hardware</span></a>
                                </li>
                                <li class="nav-mb-item">
                                    <a href="#" class="mb-menu-link"><span>Bestseller</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-bottom">
            <div class="bottom-bar-language bar-lang">
                <div class="tf-curs">
                    <select class="image-select center style-default type-cur">
                        <option selected>USD | United States</option>
                        <option>EUR | France</option>
                        <option>EUR | Germany</option>
                        <option>VND | Vietnam</option>
                    </select>
                </div>
                <div class="tf-lans">
                    <select class="image-select center style-default type-lan">
                        <option>English</option>
                        <option>العربية</option>
                        <option>简体中文</option>
                        <option>اردو</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Mobile Menu -->

<!-- Login -->
<div class="modal modalCentered fade modal-log" id="log">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon icon-close btn-hide-popup" data-bs-dismiss="modal"></span>
            <div class="modal-log-wrap list-file-delete">
                <h5 class="title fw-semibold">Log In</h5>
                <form class="form-log">
                    <div class="form-content">
                        <fieldset>
                            <label class="fw-semibold body-md-2">
                                Phone number *
                            </label>
                            <input type="text" placeholder="Your email">
                        </fieldset>
                        <fieldset>
                            <label class="fw-semibold body-md-2">
                                Password *
                            </label>
                            <input type="password" placeholder="Enter your password">
                        </fieldset>
                        <a href="#" class="link text-end body-text-3">
                            Forgot password ?
                        </a>
                    </div>
                    <button type="submit" class="tf-btn w-100 text-white">
                        Login
                    </button>
                    <p class="body-text-3 text-center">
                        Don't you have an account?
                        <a href="#register" data-bs-toggle="modal" class="text-primary">
                            Register
                        </a>
                    </p>
                </form>
                <div class=" orther-log text-center">
                    <span class="br-line bg-gray-5"></span>
                    <p class="caption text-main-2 ">
                        Or login with
                    </p>
                </div>
                <ul class="list-log">
                    <li>
                        <a href="#" class="tf-btn btn-line w-100">
                            <i class="icon icon-facebook-2"></i>
                            <span class="body-md-2 fw-semibold">Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="tf-btn btn-line w-100">
                            <i class="icon icon-google"></i>
                            <span class="body-md-2 fw-semibold">Google</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Login -->

<!-- Register -->
<div class="modal modalCentered fade modal-log" id="register">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon icon-close btn-hide-popup" data-bs-dismiss="modal"></span>
            <div class="modal-log-wrap list-file-delete">
                <h5 class="title fw-semibold">Sign Up</h5>
                <form class="form-log">
                    <div class="form-content">
                        <fieldset>
                            <label class="fw-semibold body-md-2">
                                Phone number *
                            </label>
                            <input type="text" placeholder="Your email">
                        </fieldset>
                    </div>
                    <button type="submit" class="tf-btn w-100 text-white">
                        Sign Up
                    </button>
                    <p class="body-text-3 text-center">
                        Already have an account?
                        <a href="#log" data-bs-toggle="modal" class="text-primary">
                            Sign in
                        </a>
                    </p>
                </form>
                <div class="orther-log text-center">
                    <span class="br-line bg-gray-5"></span>
                    <p class="caption text-main-2 ">
                        Or login with
                    </p>
                </div>
                <ul class="list-log">
                    <li>
                        <a href="#" class="tf-btn btn-line w-100">
                            <i class="icon icon-facebook-2"></i>
                            <span class="body-md-2 fw-semibold">Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="tf-btn btn-line w-100">
                            <i class="icon icon-google"></i>
                            <span class="body-md-2 fw-semibold">Google</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Register -->

<!-- Toolbar -->
<div class="tf-toolbar-bottom d-xl-none">
    <div class="toolbar-item">
        <a href="{{ route('home') }}">
            <span class="toolbar-icon">
                <svg width="20" height="20" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6.5 1H2.5C1.5 1 1 1.5 1 2.5V6.5C1 7.5 1.5 8 2.5 8H6.5C7.5 8 8 7.5 8 6.5V2.5C8 1.5 7.5 1 6.5 1ZM7 6.5C7 6.75 6.75 7 6.5 7H2.5C2.25 7 2 6.75 2 6.5V2.5C2 2.25 2.25 2 2.5 2H6.5C6.75 2 7 2.25 7 2.5V6.5Z"
                        fill="black"></path>
                    <path
                        d="M17.5 1H13.5C12.5 1 12 1.5 12 2.5V6.5C12 7.5 12.5 8 13.5 8H17.5C18.5 8 19 7.5 19 6.5V2.5C19 1.5 18.5 1 17.5 1ZM18 6.5C18 6.75 17.75 7 17.5 7H13.5C13.25 7 13 6.75 13 6.5V2.5C13 2.25 13.25 2 13.5 2H17.5C17.75 2 18 2.25 18 2.5V6.5Z"
                        fill="black"></path>
                    <path
                        d="M6.5 12H2.5C1.5 12 1 12.5 1 13.5V17.5C1 18.5 1.5 19 2.5 19H6.5C7.5 19 8 18.5 8 17.5V13.5C8 12.5 7.5 12 6.5 12ZM7 17.5C7 17.75 6.75 18 6.5 18H2.5C2.25 18 2 17.75 2 17.5V13.5C2 13.25 2.25 13 2.5 13H6.5C6.75 13 7 13.25 7 13.5V17.5Z"
                        fill="black"></path>
                    <path
                        d="M17.5 12H13.5C12.5 12 12 12.5 12 13.5V17.5C12 18.5 12.5 19 13.5 19H17.5C18.5 19 19 18.5 19 17.5V13.5C19 12.5 18.5 12 17.5 12ZM18 17.5C18 17.75 17.75 18 17.5 18H13.5C13.25 18 13 17.75 13 17.5V13.5C13 13.25 13.25 13 13.5 13H17.5C17.75 13 18 13.25 18 13.5V17.5Z"
                        fill="black"></path>
                </svg>

            </span>
            <span class="toolbar-label">Home</span>
        </a>
    </div>
    <div class="toolbar-item">
        @auth
        <a href="{{ route('my.account') }}">
        @else
        <a href="#log" data-bs-toggle="modal">
        @endauth
            <span class="toolbar-icon">
                <svg width="20" height="20" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10.9998 11.5283C5.20222 11.5283 0.485352 16.2452 0.485352 22.0428C0.485352 22.2952 0.69017 22.5 0.942518 22.5C1.19487 22.5 1.39968 22.2952 1.39968 22.0428C1.39968 16.749 5.70606 12.4426 10.9999 12.4426C16.2937 12.4426 20.6001 16.749 20.6001 22.0428C20.6001 22.2952 20.8049 22.5 21.0572 22.5C21.3096 22.5 21.5144 22.2952 21.5144 22.0428C21.5144 16.2443 16.7975 11.5283 10.9998 11.5283Z"
                        fill="#333E48" stroke="#333E48" stroke-width="0.3"></path>
                    <path
                        d="M10.9999 0.5C8.22767 0.5 5.97119 2.75557 5.97119 5.52866C5.97119 8.30174 8.22771 10.5573 10.9999 10.5573C13.772 10.5573 16.0285 8.30174 16.0285 5.52866C16.0285 2.75557 13.772 0.5 10.9999 0.5ZM10.9999 9.64303C8.73146 9.64303 6.88548 7.79705 6.88548 5.52866C6.88548 3.26027 8.73146 1.41429 10.9999 1.41429C13.2682 1.41429 15.1142 3.26027 15.1142 5.52866C15.1142 7.79705 13.2682 9.64303 10.9999 9.64303Z"
                        fill="#333E48" stroke="#333E48" stroke-width="0.3"></path>
                </svg>

            </span>
            <span class="toolbar-label">Account</span>
        </a>
    </div>
    <div class="toolbar-item">
        <a href="#search" data-bs-toggle="offcanvas">
            <span class="toolbar-icon">
                <svg width="20" height="20" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.795 17.8101L13.5309 13.5225C14.9671 12.072 15.7894 10.1219 15.8267 8.07859C15.864 6.03526 15.1132 4.05635 13.7309 2.55423C12.3486 1.05211 10.4414 0.142631 8.40671 0.0154012C6.37204 -0.111829 4.36687 0.552966 2.80908 1.87124C1.25129 3.18952 0.261042 5.05958 0.0447082 7.09172C-0.171625 9.12385 0.402641 11.1613 1.64783 12.7795C2.89302 14.3976 4.71308 15.4716 6.72871 15.7777C8.74435 16.0838 10.8001 15.5983 12.4674 14.4225L16.8162 18.7977C16.8809 18.8622 16.9576 18.9132 17.042 18.9479C17.1264 18.9826 17.2167 19.0003 17.3079 19C17.3991 18.9997 17.4893 18.9814 17.5735 18.9461C17.6576 18.9109 17.734 18.8593 17.7982 18.7945C17.8625 18.7296 17.9134 18.6527 17.948 18.5682C17.9826 18.4836 18.0003 18.393 18 18.3016C17.9997 18.2102 17.9814 18.1197 17.9463 18.0354C17.9111 17.951 17.8597 17.8745 17.795 17.8101ZM1.49742 7.97499C1.50151 6.69476 1.88395 5.44444 2.59643 4.38196C3.30892 3.31948 4.31949 2.49252 5.50052 2.00544C6.68156 1.51836 7.98009 1.39299 9.23213 1.64527C10.4842 1.89754 11.6336 2.51614 12.5351 3.42285C13.4367 4.32957 14.0501 5.48378 14.2977 6.73971C14.5454 7.99565 14.4162 9.29694 13.9265 10.4793C13.4369 11.6617 12.6087 12.6722 11.5466 13.383C10.4844 14.0939 9.23598 14.4733 7.95891 14.4733C7.10897 14.4721 6.26759 14.303 5.48285 13.9757C4.6981 13.6485 3.98535 13.1694 3.38531 12.566C2.78528 11.9625 2.30971 11.2465 1.98578 10.4588C1.66185 9.67104 1.49591 8.82703 1.49742 7.97499Z"
                        fill="#333E48" />
                </svg>
            </span>
            <span class="toolbar-label">Search</span>
        </a>
    </div>
    <div class="toolbar-item">
        <a href="{{ route('wishlist') }}">
            <span class="toolbar-icon">
                <svg width="20" height="20" viewBox="0 0 24 26" stroke-width="0.3" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.1949 2.23732C15.1929 2.22573 13.2896 3.17827 12 4.83729C10.7184 3.16693 8.80988 2.21168 6.80503 2.23732C3.0467 2.23732 0 5.53791 0 9.60943C0 16.5867 11.2405 23.3993 11.6962 23.6626C11.8801 23.7957 12.1199 23.7957 12.3038 23.6626C12.7595 23.3993 24 16.6854 24 9.60943C24 5.53791 20.9532 2.23732 17.1949 2.23732ZM12 22.3461C10.238 21.2272 1.21519 15.2702 1.21519 9.60943C1.21519 6.26499 3.71785 3.55371 6.80508 3.55371C8.69561 3.52682 10.4648 4.55986 11.4836 6.28534C11.6904 6.59433 12.0893 6.66318 12.3746 6.43905C12.4291 6.39621 12.477 6.34437 12.5164 6.28534C14.206 3.48618 17.6702 2.70077 20.2541 4.53107C21.8358 5.65155 22.7879 7.56199 22.7848 9.60937C22.7848 15.336 13.762 21.2601 12 22.3461Z"
                        fill="#333E48"></path>
                </svg>
                <span class="toolbar-count wishlist-count">{{ Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->getTotalQuantity() }}</span>
            </span>
            <span class="toolbar-label">Wishlist</span>
        </a>
    </div>
    <div class="toolbar-item">
        <a href="#shoppingCart" data-bs-toggle="offcanvas">
            <span class="toolbar-icon">
                <svg width="20" height="20" viewBox="0 0 26 26" stroke-width="0.3" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.55865 19.1096C6.8483 19.1096 5.46191 20.496 5.46191 22.2064C5.46191 23.9165 6.8483 25.3029 8.55865 25.3029C10.2688 25.3029 11.6552 23.9165 11.6552 22.2064C11.6534 20.4969 10.2681 19.1114 8.55865 19.1096ZM8.55865 24.1644C7.47712 24.1644 6.60037 23.2877 6.60037 22.2064C6.60037 21.1248 7.47712 20.2481 8.55865 20.2481C9.63996 20.2481 10.5167 21.1248 10.5167 22.2064C10.5167 23.2877 9.63996 24.1644 8.55865 24.1644Z"
                        fill="#333E48"></path>
                    <path
                        d="M25.436 6.1144H5.33643L4.92663 3.82036C4.67403 2.40819 3.56715 1.30353 2.15453 1.05382L0.668757 0.792113C0.359017 0.736969 0.0635073 0.943536 0.00836329 1.25305C-0.0465584 1.56279 0.159787 1.8583 0.469527 1.91345L1.96086 2.17516C2.90187 2.34193 3.63853 3.07859 3.80529 4.01959L5.82027 15.387C6.05819 16.7472 7.24001 17.7393 8.62083 17.738H20.5746C21.8305 17.7418 22.9396 16.9197 23.3014 15.7172L25.9767 6.84861C26.0263 6.67562 25.995 6.48929 25.8913 6.34209C25.7831 6.19956 25.6147 6.11551 25.436 6.1144ZM22.214 15.3813C21.9992 16.1035 21.3337 16.5975 20.5804 16.5938H8.62661C7.79745 16.596 7.08769 15.9994 6.94739 15.182L5.54144 7.24707H24.6731L22.214 15.3813Z"
                        fill="#333E48"></path>
                    <path
                        d="M20.512 19.1096C18.8017 19.1096 17.4153 20.496 17.4153 22.2064C17.4153 23.9165 18.8017 25.3029 20.512 25.3029C22.2221 25.3029 23.6085 23.9165 23.6085 22.2064C23.6068 20.4969 22.2215 19.1114 20.512 19.1096ZM20.512 24.1644C19.4305 24.1644 18.5537 23.2877 18.5537 22.2064C18.5537 21.1248 19.4305 20.2481 20.512 20.2481C21.5933 20.2481 22.4701 21.1248 22.4701 22.2064C22.4701 23.2877 21.5933 24.1644 20.512 24.1644Z"
                        fill="#333E48"></path>
                </svg>
                <span class="toolbar-count cart-count">{{ Darryldecode\Cart\Facades\CartFacade::session(Auth::id() ?? session()->getId())->getTotalQuantity() }}</span>
            </span>
            <span class="toolbar-label">Cart</span>
        </a>
    </div>
</div>
<!-- /Toolbar -->

<!-- Search -->
<div class="offcanvas offcanvas-top offcanvas-search" id="search">
    <div class="offcanvas-content">
        <div class="popup-header">
            <button class="icon-close icon-close-popup link" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="looking-for-wrap">
                        <h3 class="heading fw-semibold text-center">What are you looking for?</h3>
                        <form class="form-search" action="{{ route('shop') }}" method="GET">
                            <fieldset>
                                <input class="" type="text" placeholder="Search for anything" name="search"
                                    tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                            </fieldset>
                            <button type="submit" class="button-submit">
                                <i class="icon-search"></i>
                            </button>
                        </form>
                        <div class="popular-searches justify-content-md-center">
                            <span class="text fw-semibold body-text-3">Popular searches:</span>
                            <ul>
                                <li><a class="link body-text-3 fw-medium" href="#">Featured</a></li>
                                <li><a class="link body-text-3 fw-medium" href="#">Trendy</a></li>
                                <li><a class="link body-text-3 fw-medium" href="#">New</a></li>
                                <li><a class="link body-text-3 fw-medium" href="#">Sale</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- /Search -->

<script>
    window.CartItemsInSession = @json(\Cart::session(Auth::id() ?? session()->getId())->getContent()->mapWithKeys(function($item) {
        return [$item->id => [
            'product_id' => $item->attributes->product_id,
            'variant_id' => $item->attributes->variant_id,
        ]];
    })->toArray());
</script>

