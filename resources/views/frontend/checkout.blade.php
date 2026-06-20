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
                $settings = \App\Models\Setting::first();
                $shippingActive = $settings && $settings->shipping_active;
                $shippingInside = $settings ? floatval($settings->shipping_inside) : 60;
                $shippingOutside = $settings ? floatval($settings->shipping_outside) : 100;
                $cartSubtotal = $cart->getSubTotal();
                $threshold = floatval(config('cart.free_shipping_threshold', 250));
                $isFreeShipping = ($cartSubtotal >= $threshold);
            @endphp


            @if($items->count() > 0)
            <form action="{{ route('place.order') }}" method="POST" id="checkout-form">
                @csrf
                <div class="tf-checkout-wrap flex-lg-nowrap">
                    <div class="page-checkout">
                        {{-- <div class="wrap">
                            <h5 class="title has-account">
                                <span class="fw-semibold">Contact</span>
                                @guest
                                    <span class="body-text-3">Have an account? <a href="#register" data-bs-toggle="modal" class="body-text-3 text-secondary link">Login</a></span>
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
                        </div> --}}
                        <div class="wrap">
                            <h5 class="title fw-semibold">
                                Delivery Details
                            </h5>
                            <div class="def">
                                <div class="cols">
                                    <fieldset>
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Jonn" required>
                                    </fieldset>
                                    <fieldset>
                                        <label>Phone number <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. +8801700000000" pattern="[0-9]{11}" maxlength="11" required>
                                    </fieldset>
                                </div>
                                <fieldset>
                                    <label>Street Address <span class="text-danger">*</span></label>
                                    <textarea name="address" placeholder="Your detailed street address">{{ old('address') }}</textarea>
                                </fieldset>
                                @if($shippingActive)
                                <fieldset>
                                    <label>Shipping <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-column gap-0 border rounded-1 overflow-hidden">
                                        <!-- Option 1: Inside Dhaka -->
                                        <label class="d-flex justify-content-between align-items-center bg-white px-3 py-2 border-bottom checkout-shipping-option" style="cursor: pointer;">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input my-0 me-3 shipping-radio" type="radio" name="shipping_method" id="inside_dhaka" value="{{ $shippingInside }}" checked style="width: 1.15rem; height: 1.15rem;">
                                                <span class="text-secondary" style="font-size: 0.95rem;">ঢাকার ভিতরে:</span>
                                            </div>
                                            <span class="fw-normal text-success" style="font-size: 0.95rem;">TK {{ number_format($shippingInside, 2) }}</span>
                                        </label>

                                        <!-- Option 2: Outside Dhaka -->
                                        <label class="d-flex justify-content-between align-items-center bg-white px-3 py-2 checkout-shipping-option" style="cursor: pointer;">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input my-0 me-3 shipping-radio" type="radio" name="shipping_method" id="outside_dhaka" value="{{ $shippingOutside }}" style="width: 1.15rem; height: 1.15rem;">
                                                <span class="text-secondary" style="font-size: 0.95rem;">ঢাকার বাহিরে:</span>
                                            </div>
                                            <span class="fw-normal text-success" style="font-size: 0.95rem;">TK {{ number_format($shippingOutside, 2) }}</span>
                                        </label>
                                    </div>
                                    @else
                                        <input type="hidden" name="shipping_method" value="0">
                                    @endif
                                </fieldset>
                                <fieldset>
                                    <label>Order note</label>
                                    <textarea name="note" placeholder="Note on your order (optional)">{{ old('note') }}</textarea>
                                </fieldset>
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
                                <li><span class="body-text-3">Subtotal</span><span class="body-text-3" id="checkout-subtotal">TK {{ number_format($cartSubtotal, 2) }}</span></li>
                                <li><span class="body-text-3">Shipping</span><span class="body-text-3" id="checkout-shipping">@if($shippingActive && !$isFreeShipping) TK {{ number_format($shippingInside, 2) }} @else Free Shipping @endif</span></li>
                                <li><span class="body-md-2 fw-semibold">Total</span><span class="body-md-2 fw-semibold text-primary" id="checkout-total">TK {{ number_format($cartSubtotal + ($shippingActive && !$isFreeShipping ? $shippingInside : 0), 2) }}</span></li>
                            </ul>
                            @if($shippingActive)
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var subtotal = {{ $cartSubtotal }};
                                var threshold = {{ $threshold }};
                                var shippingEls = document.querySelectorAll('.shipping-radio');
                                var shippingDisplay = document.getElementById('checkout-shipping');
                                var totalDisplay = document.getElementById('checkout-total');
                                function updateTotal() {
                                    var selectedEl = document.querySelector('.shipping-radio:checked');
                                    var shipping = selectedEl ? parseFloat(selectedEl.value) : 0;
                                    if (subtotal >= threshold) {
                                        shipping = 0;
                                    }
                                    var total = subtotal + shipping;
                                    if (subtotal >= threshold) {
                                        shippingDisplay.textContent = 'Free Shipping';
                                    } else {
                                        shippingDisplay.textContent = 'TK ' + shipping.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    }
                                    totalDisplay.textContent = 'TK ' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                }
                                shippingEls.forEach(function(el) { el.addEventListener('change', updateTotal); });
                                updateTotal();
                            });
                            </script>
                            @endif
                        </div>

                        <div class="box-btn mt-4">
                            <button type="submit" class="tf-btn w-100">
                                <span class="text-white">Place order</span>
                            </button>
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

    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif


    @push('js')
        <script>
            dataLayer.push({ ecommerce: null });
            dataLayer.push({
                'event': 'begin_checkout',
                'ecommerce': {
                    'currency': 'BDT',
                    'value': {{ \Cart::getTotal() }},
                    'items': [
                        @foreach (\Cart::getContent() as $item)
                        {
                            'item_id': '{{ $item->associatedModel->id ?? $item->id }}',
                            'item_name': '{{ $item->name }}',
                            'item_category': '{{ $item->associatedModel->category->name ?? '' }}',
                            'price': {{ $item->price }},
                            'quantity': {{ $item->quantity }}
                        },
                        @endforeach
                    ]
                }
            });

            fbq('track', 'InitiateCheckout', {
                content_ids: [@foreach (\Cart::getContent() as $item) '{{ $item->associatedModel->id ?? $item->id }}', @endforeach],
                content_type: 'product',
                value: {{ \Cart::getTotal() }},
                currency: 'BDT',
                num_items: {{ \Cart::getContent()->count() }}
            });
        </script>
    @endpush
</x-frontend-layout>
