<x-frontend-layout title="Checkout" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small"> Check Out</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Check Out Cart -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar next"></span>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="{{ route('cart') }}" class="link body-text-3">Shopping Cart</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="{{ route('checkout') }}" class="text-secondary link body-text-3">Shopping & Checkout</a>

                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <a href="javascript:void(0);" class="link body-text-3">Confirmation</a>
                    </div>
                </div>
            </div>

            @php
                $cart = \Cart::session(Auth::id() ?? session()->getId());
                $items = $cart->getContent();
            @endphp

            @if($items->count() > 0)
            <form action="{{ route('place.order') }}" method="POST" id="checkout-form">
                @csrf
                <div class="tf-checkout-wrap flex-lg-nowrap">
                    <div class="page-checkout">
                        <div class="wrap">
                            <h5 class="title has-account">
                                <span class="fw-semibold">Contact</span>
                                @guest
                                <span class="body-text-3">Have an account? <a href="#register" data-bs-toggle="modal"
                                        class="body-text-3 text-secondary link">Login</a></span>
                                @endguest
                            </h5>
                            @if(session('error'))
                                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="body-md-2 fw-semibold mb-1">Email Address <span class="text-danger">*</span></label>
                                    <input class="def" type="email" name="email" value="{{ Auth::user()->email ?? old('email') }}" placeholder="john@example.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="body-md-2 fw-semibold mb-1">Phone Number <span class="text-danger">*</span></label>
                                    <input class="def" type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +8801700000000" required>
                                </div>
                            </div>
                        </div>
                        <div class="wrap">
                            <h5 class="title fw-semibold">
                                Delivery Details
                            </h5>
                            <div class="def">
                                <div class="cols">
                                    <fieldset>
                                        <label>First name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Jonn" required>
                                    </fieldset>
                                    <fieldset>
                                        <label>Last name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Doe" required>
                                    </fieldset>
                                </div>
                                <div class="cols">
                                    <fieldset style="flex: 2;">
                                        <label>City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Dhaka" required>
                                    </fieldset>
                                    <fieldset style="flex: 1;">
                                        <label>ZIP code</label>
                                        <input type="text" name="zip" value="{{ old('zip') }}" placeholder="e.g. 1200">
                                    </fieldset>
                                </div>
                                <fieldset>
                                    <label>Street Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Your detailed street address" required>
                                </fieldset>
                                <fieldset>
                                    <label>Order note</label>
                                    <textarea name="note" placeholder="Note on your order (optional)">{{ old('note') }}</textarea>
                                </fieldset>
                            </div>
                        </div>
                        <div class="wrap">
                            <h5 class="title">
                                Payment Method
                            </h5>
                            <div class="form-payment">
                                <div class="payment-box" id="payment-box">
                                    <div class="payment-item payment-choose-card active">
                                        <label for="delivery-method" class="payment-header radio-item d-flex align-items-center cursor-pointer w-100"
                                            data-bs-toggle="collapse" data-bs-target="#delivery-payment" aria-controls="delivery-payment" aria-expanded="true">
                                            <input type="radio" name="payment_method" value="cod" class="tf-check-rounded mr-2"
                                                id="delivery-method" checked>
                                            <span class="body-text-3 fw-semibold">Cash on delivery</span>
                                        </label>
                                    </div>
                                    <div class="payment-item">
                                        <label for="pickup-method" class="payment-header radio-item collapsed d-flex align-items-center cursor-pointer w-100"
                                            data-bs-toggle="collapse" data-bs-target="#pickup-payment" aria-controls="pickup-payment" aria-expanded="false">
                                            <input type="radio" name="payment_method" value="cash" class="tf-check-rounded mr-2"
                                                id="pickup-method">
                                            <span class="body-text-3 fw-semibold">Cash Pickup</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="box-btn">
                                    <button type="submit" class="tf-btn w-100">
                                        <span class="text-white">Place order</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flat-sidebar-checkout">
                        <div class="sidebar-checkout-content">
                            <h5 class="fw-semibold">Order Summary</h5>
                            <ul class="list-product">
                                @foreach($items as $item)
                                @php
                                    $product = $item->associatedModel;
                                    $mainImage = $product && $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
                                    $productUrl = $product ? route('product.show', $product->slug) : '#';
                                @endphp
                                <li class="item-product">
                                    <a href="{{ $productUrl }}" class="img-product">
                                        <img src="{{ $mainImage }}" alt="{{ $item->name }}">
                                    </a>
                                    <div class="content-box">
                                        <a href="{{ $productUrl }}" class="link-secondary body-md-2 fw-semibold">
                                            {{ $item->name }}
                                        </a>
                                        <p class="price-quantity price-text fw-semibold">
                                            TK {{ number_format($item->price, 2) }}
                                            <span class="body-md-2 text-main-2 fw-normal">X{{ $item->quantity }}</span>
                                        </p>
                                        @php
                                            $variantAttributes = $item->attributes->variant_attributes
                                                ?? $item->attributes->attributes
                                                ?? [];

                                            if (is_string($variantAttributes)) {
                                                $decodedVariantAttributes = json_decode($variantAttributes, true);
                                                $variantAttributes = is_array($decodedVariantAttributes) ? $decodedVariantAttributes : [];
                                            }

                                            $variantLabel = $item->attributes->variant_label ?? null;
                                        @endphp

                                        @if(is_array($variantAttributes) && count($variantAttributes) > 0)
                                            @php
                                                $pairs = [];
                                                foreach ($variantAttributes as $k => $v) {
                                                    $pairs[] = ucfirst($k) . ': ' . $v;
                                                }
                                                $variantLine = implode(', ', $pairs);
                                            @endphp
                                            <p class="body-md-2 text-main-2"><strong>Variant:</strong> {{ $variantLine }}</p>
                                        @elseif($variantLabel)
                                            <p class="body-md-2 text-main-2"><strong>Variant:</strong> {{ $variantLabel }}</p>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="d-none">
                                <p class="body-md-2 fw-semibold sub-type">Discount code</p>
                                <div class="ip-discount-code style-2">
                                    <input type="text" class="def" placeholder="Your code">
                                    <button type="button" class="tf-btn btn-gray-2">
                                        <span>Apply</span>
                                    </button>
                                </div>
                            </div>
                            <ul class="sec-total-price">
                                <li><span class="body-text-3">Subtotal</span><span class="body-text-3">TK {{ number_format($cart->getSubTotal(), 2) }}</span>
                                </li>
                                <li><span class="body-text-3">Shipping</span><span class="body-text-3">Free shipping</span></li>
                                <li><span class="body-md-2 fw-semibold">Total</span><span
                                        class="body-md-2 fw-semibold text-primary">TK {{ number_format($cart->getTotal(), 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="icon icon-shop-cart-1" style="font-size: 80px; color: #ccc;"></i>
                    </div>
                    <h3 class="fw-semibold">Your shopping cart is empty</h3>
                    <p class="text-muted my-3">You must add some items to your shopping cart before checking out.</p>
                    <a href="{{ route('shop') }}" class="tf-btn"><span class="text-white">Continue Shopping</span></a>
                </div>
            @endif
        </div>
    </section>
    <!-- /Check Out Cart -->
        </div>
    </section>
    <!-- /Check Out Cart -->
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
