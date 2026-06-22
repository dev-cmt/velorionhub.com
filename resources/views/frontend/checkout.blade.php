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
                            <ul class="list-product" id="checkout-items-list">
                                @foreach($items as $item)
                                @php
                                    $product = $item->associatedModel;
                                    $mainImage = $product && $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
                                    $productUrl = $product ? route('product.show', $product->slug) : '#';
                                @endphp
                                @php
                                    $variantAttributes = $item->attributes->variant_attributes
                                        ?? $item->attributes->attributes
                                        ?? [];

                                    if (is_string($variantAttributes)) {
                                        $decodedVariantAttributes = json_decode($variantAttributes, true);
                                        $variantAttributes = is_array($decodedVariantAttributes) ? $decodedVariantAttributes : [];
                                    }

                                    $variantLabel = $item->attributes->variant_label ?? null;
                                    $hasVariant = $product && $product->has_variant;
                                    $productId = $product ? $product->id : null;
                                @endphp
                                <li class="item-product checkout-cart-row" data-item-id="{{ $item->id }}" data-product-id="{{ $productId }}">
                                    <a href="{{ $productUrl }}" class="img-product">
                                        <img src="{{ $mainImage }}" alt="{{ $item->name }}">
                                    </a>
                                    <div class="content-box flex-grow-1">
                                        <a href="{{ $productUrl }}" class="link-secondary body-md-2 fw-semibold d-block mb-1">
                                            {{ $item->name }}
                                        </a>

                                        {{-- Variant Label --}}
                                        @if(is_array($variantAttributes) && count($variantAttributes) > 0)
                                            @php
                                                $pairs = [];
                                                foreach ($variantAttributes as $k => $v) {
                                                    $pairs[] = ucfirst($k) . ': ' . $v;
                                                }
                                                $variantLine = implode(', ', $pairs);
                                            @endphp
                                            <p class="body-md-2 text-main-2 item-variant-label mb-1"><strong>Variant:</strong> {{ $variantLine }}</p>
                                        @elseif($variantLabel)
                                            <p class="body-md-2 text-main-2 item-variant-label mb-1"><strong>Variant:</strong> {{ $variantLabel }}</p>
                                        @endif

                                        {{-- Price & Qty row --}}
                                        <div class="d-flex align-items-center justify-content-between gap-2 mt-2 flex-wrap">
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Qty Controls --}}
                                                <div class="checkout-qty-wrap d-flex align-items-center border rounded-1 overflow-hidden" style="height:32px;">
                                                    <button type="button" class="checkout-qty-btn btn-qty-minus px-2 border-0 bg-white" data-id="{{ $item->id }}" data-product-id="{{ $productId }}" style="font-size:16px;line-height:1;cursor:pointer;">−</button>
                                                    <span class="checkout-qty-val px-2 fw-semibold" style="min-width:28px;text-align:center;font-size:14px;">{{ $item->quantity }}</span>
                                                    <button type="button" class="checkout-qty-btn btn-qty-plus px-2 border-0 bg-white" data-id="{{ $item->id }}" data-product-id="{{ $productId }}" style="font-size:16px;line-height:1;cursor:pointer;">+</button>
                                                </div>
                                                <span class="item-unit-price text-main-2 body-text-3" style="font-size:12px;">× TK <span>{{ number_format($item->price, 2) }}</span></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="price-text fw-semibold item-row-total" data-price="{{ $item->price }}" data-qty="{{ $item->quantity }}">
                                                    TK {{ number_format($item->price * $item->quantity, 2) }}
                                                </span>
                                                @if($hasVariant)
                                                <button type="button"
                                                    class="btn-change-variant"
                                                    data-item-id="{{ $item->id }}"
                                                    data-product-id="{{ $productId }}"
                                                    data-qty="{{ $item->quantity }}"
                                                    style="background:none;border:1px solid #6c757d;border-radius:4px;font-size:11px;padding:2px 8px;color:#6c757d;cursor:pointer;white-space:nowrap;transition:all .2s;"
                                                    onmouseover="this.style.background='#6c757d';this.style.color='#fff';"
                                                    onmouseout="this.style.background='none';this.style.color='#6c757d';">
                                                    ✎ Change
                                                </button>
                                                @endif
                                            </div>
                                        </div>
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
                            {{-- Shipping total recalc is handled by the jQuery block in @push('js') --}}
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

    {{-- ===================== VARIANT CHANGE MODAL ===================== --}}
    <div class="modal fade" id="variantChangeModal" tabindex="-1" aria-labelledby="variantChangeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content rounded-3 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-semibold" id="variantChangeModalLabel">Change Variant</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2" id="variant-modal-body">
                    <div class="text-center py-4" id="variant-modal-loading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <p class="mt-2 text-muted body-text-3">Loading variants…</p>
                    </div>
                    <div id="variant-modal-content" class="d-none">
                        <p class="body-md-2 fw-semibold mb-3" id="modal-product-name"></p>
                        <div id="modal-attribute-groups"></div>
                        <p class="text-danger body-text-3 mt-2 d-none" id="modal-out-of-stock">⚠ This combination is out of stock.</p>
                        <p class="body-text-3 text-muted mt-1" id="modal-selected-price" style="font-size:13px;"></p>
                        {{-- Qty inside modal --}}
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <span class="body-text-3 fw-semibold">Qty:</span>
                            <div class="d-flex align-items-center border rounded-1 overflow-hidden" style="height:34px;">
                                <button type="button" id="modal-qty-minus" class="px-2 border-0 bg-white" style="font-size:16px;cursor:pointer;">−</button>
                                <input type="number" id="modal-qty-val" value="1" min="1" class="border-0 text-center fw-semibold" style="width:42px;font-size:14px;outline:none;">
                                <button type="button" id="modal-qty-plus" class="px-2 border-0 bg-white" style="font-size:16px;cursor:pointer;">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="tf-btn w-100" id="modal-apply-btn" disabled>
                        <span class="text-white">Apply Changes</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- =================== END VARIANT MODAL =================== --}}

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

            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout', {
                    content_ids: [@foreach (\Cart::getContent() as $item) '{{ $item->associatedModel->id ?? $item->id }}', @endforeach],
                    content_type: 'product',
                    value: {{ \Cart::getTotal() }},
                    currency: 'BDT',
                    num_items: {{ \Cart::getContent()->count() }}
                });
            }
        </script>

        <script>
        // ─── CHECKOUT CART JS ────────────────────────────────────────────────────
        $(function () {
            var CSRF     = '{{ csrf_token() }}';
            var UPDATE_URL  = '{{ route("cart.update-item") }}';
            var VARIANTS_URL = '{{ route("cart.product-variants") }}';
            var FREE_THRESHOLD = {{ $threshold }};
            @if($shippingActive)
            var SHIPPING_INSIDE  = {{ $shippingInside }};
            var SHIPPING_OUTSIDE = {{ $shippingOutside }};
            @else
            var SHIPPING_INSIDE  = 0;
            var SHIPPING_OUTSIDE = 0;
            @endif

            /* ─── HELPERS ─────────────────────────────────────────── */
            function fmtTK(val) {
                return 'TK ' + parseFloat(val).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function recalcTotals() {
                var subtotal = 0;
                $('#checkout-items-list .checkout-cart-row').each(function () {
                    var rowTotal = $(this).find('.item-row-total');
                    var price = parseFloat(rowTotal.data('price')) || 0;
                    var qty   = parseInt($(this).find('.checkout-qty-val').text()) || 1;
                    subtotal += price * qty;
                });

                var selectedShippingEl = $('input[name="shipping_method"]:checked');
                var shipping = selectedShippingEl.length ? parseFloat(selectedShippingEl.val()) : 0;
                if (subtotal >= FREE_THRESHOLD) shipping = 0;

                var total = subtotal + shipping;

                $('#checkout-subtotal').text(fmtTK(subtotal));
                if (subtotal >= FREE_THRESHOLD) {
                    $('#checkout-shipping').text('Free Shipping');
                } else {
                    $('#checkout-shipping').text(fmtTK(shipping));
                }
                $('#checkout-total').text(fmtTK(total));
            }

            /* Update shipping on change */
            $(document).on('change', 'input[name="shipping_method"]', function () {
                recalcTotals();
            });

            /* ─── QUANTITY +/− (checkout sidebar) ────────────────── */
            function setRowLoading($row, on) {
                $row.css('opacity', on ? 0.5 : 1).find('.checkout-qty-btn').prop('disabled', on);
            }

            function updateQtyAjax($row, newQty) {
                var itemId    = $row.data('item-id');
                var productId = $row.data('product-id');
                setRowLoading($row, true);

                $.ajax({
                    url   : UPDATE_URL,
                    method: 'POST',
                    data  : { _token: CSRF, old_id: itemId, product_id: productId, qty: newQty },
                    success: function (res) {
                        if (res.success) {
                            $row.find('.checkout-qty-val').text(newQty);
                            var price = parseFloat($row.find('.item-row-total').data('price'));
                            $row.find('.item-row-total').data('qty', newQty).text(fmtTK(price * newQty));
                            recalcTotals();
                        }
                    },
                    error: function () { alert('Could not update quantity. Please try again.'); },
                    complete: function () { setRowLoading($row, false); }
                });
            }

            $(document).on('click', '.btn-qty-plus', function () {
                var $row = $(this).closest('.checkout-cart-row');
                var cur  = parseInt($row.find('.checkout-qty-val').text()) || 1;
                updateQtyAjax($row, cur + 1);
            });

            $(document).on('click', '.btn-qty-minus', function () {
                var $row = $(this).closest('.checkout-cart-row');
                var cur  = parseInt($row.find('.checkout-qty-val').text()) || 1;
                if (cur > 1) updateQtyAjax($row, cur - 1);
            });

            /* ─── VARIANT CHANGE MODAL ────────────────────────────── */
            var _activeItemId   = null;
            var _activeProductId= null;
            var _allVariants    = [];
            var _selectedAttrIds= {};

            function getSelectedAttrArray() {
                return Object.values(_selectedAttrIds).map(Number);
            }

            function findVariant() {
                var sel = getSelectedAttrArray().sort();
                return _allVariants.find(function (v) {
                    var ids = v.attr_ids.slice().sort();
                    return ids.length === sel.length && ids.every(function (id, i) { return id === sel[i]; });
                });
            }

            function renderAttributeGroups(groups) {
                var html = '';
                groups.forEach(function (group) {
                    html += '<div class="mb-3">';
                    html += '<p class="body-text-3 fw-semibold mb-2" style="text-transform:capitalize;">' + group.name + '</p>';
                    html += '<div class="d-flex flex-wrap gap-2" data-attr-name="' + group.name + '">';
                    group.items.forEach(function (item) {
                        html += '<button type="button" class="modal-attr-btn border rounded-1 px-3 py-1 body-text-3" ' +
                                'data-attr-id="' + item.id + '" data-attr-name="' + group.name + '" ' +
                                'style="cursor:pointer;transition:all .15s;background:#f8f9fa;">' +
                                item.name + '</button>';
                    });
                    html += '</div></div>';
                });
                $('#modal-attribute-groups').html(html);
            }

            function checkModalState() {
                var variant = findVariant();
                var $applyBtn = $('#modal-apply-btn');
                var $oos = $('#modal-out-of-stock');
                var $priceEl = $('#modal-selected-price');
                var allGroupsSelected = true;

                $('#modal-attribute-groups [data-attr-name]').each(function () {
                    var attrName = $(this).data('attr-name');
                    if (!_selectedAttrIds[attrName]) { allGroupsSelected = false; }
                });

                if (!allGroupsSelected) {
                    $applyBtn.prop('disabled', true);
                    $oos.addClass('d-none');
                    $priceEl.text('');
                    return;
                }

                if (!variant) {
                    $applyBtn.prop('disabled', true);
                    $oos.removeClass('d-none').text('⚠ This combination doesn\'t exist.');
                    $priceEl.text('');
                    return;
                }

                if (variant.stock <= 0) {
                    $applyBtn.prop('disabled', true);
                    $oos.removeClass('d-none').text('⚠ This variant is out of stock.');
                    $priceEl.text('Price: ' + fmtTK(variant.price));
                    return;
                }

                $oos.addClass('d-none');
                $applyBtn.prop('disabled', false);
                $priceEl.text('Price: ' + fmtTK(variant.price) + ' · Stock: ' + variant.stock);
            }

            // Attribute button selection
            $(document).on('click', '.modal-attr-btn', function () {
                var attrName = $(this).data('attr-name');
                var attrId   = $(this).data('attr-id');
                $('#modal-attribute-groups [data-attr-name="' + attrName + '"] .modal-attr-btn').each(function () {
                    $(this).css({ background: '#f8f9fa', color: '#212529', borderColor: '#dee2e6' });
                });
                $(this).css({ background: 'var(--primary, #0d6efd)', color: '#fff', borderColor: 'transparent' });
                _selectedAttrIds[attrName] = attrId;
                checkModalState();
            });

            // Modal qty
            $('#modal-qty-minus').on('click', function () {
                var v = parseInt($('#modal-qty-val').val()) || 1;
                if (v > 1) $('#modal-qty-val').val(v - 1);
            });
            $('#modal-qty-plus').on('click', function () {
                var v = parseInt($('#modal-qty-val').val()) || 1;
                $('#modal-qty-val').val(v + 1);
            });

            // Open modal
            $(document).on('click', '.btn-change-variant', function () {
                _activeItemId    = $(this).data('item-id');
                _activeProductId = $(this).data('product-id');
                _selectedAttrIds = {};
                var currentQty   = $(this).data('qty') || 1;

                $('#variant-modal-loading').removeClass('d-none');
                $('#variant-modal-content').addClass('d-none');
                $('#modal-apply-btn').prop('disabled', true);
                $('#modal-qty-val').val(currentQty);

                var modal = new bootstrap.Modal(document.getElementById('variantChangeModal'));
                modal.show();

                $.ajax({
                    url   : VARIANTS_URL,
                    method: 'GET',
                    data  : { product_id: _activeProductId },
                    success: function (res) {
                        if (!res.success) return;
                        _allVariants = res.variants;
                        $('#modal-product-name').text(res.product_name);
                        renderAttributeGroups(res.attribute_groups);
                        $('#variant-modal-loading').addClass('d-none');
                        $('#variant-modal-content').removeClass('d-none');
                        $('#modal-out-of-stock').addClass('d-none');
                        $('#modal-selected-price').text('');
                    },
                    error: function () {
                        $('#variant-modal-loading').html('<p class="text-danger">Failed to load variants.</p>');
                    }
                });
            });

            // Apply button
            $('#modal-apply-btn').on('click', function () {
                var variant  = findVariant();
                if (!variant || variant.stock <= 0) return;

                var qty      = parseInt($('#modal-qty-val').val()) || 1;
                var attrIds  = getSelectedAttrArray();

                $('#modal-apply-btn').prop('disabled', true).find('span').text('Applying…');

                $.ajax({
                    url   : UPDATE_URL,
                    method: 'POST',
                    data  : {
                        _token    : CSRF,
                        old_id    : _activeItemId,
                        product_id: _activeProductId,
                        qty       : qty,
                        attributes: attrIds,
                    },
                    success: function (res) {
                        if (res.success) {
                            // Update the row in the DOM
                            var $row = $('.checkout-cart-row[data-item-id="' + _activeItemId + '"]');

                            // Build new variant label
                            var variantNames = [];
                            $('#modal-attribute-groups .modal-attr-btn').each(function () {
                                if ($(this).css('color') === 'rgb(255, 255, 255)') { // selected
                                    variantNames.push($(this).text().trim());
                                }
                            });

                            // Update data-item-id to new sku from response (find matching item in res.items)
                            var updatedItem = res.items.find(function(i) { return parseFloat(i.price) === parseFloat(variant.price); });
                            if (updatedItem) {
                                $row.attr('data-item-id', updatedItem.id);
                                $row.find('.btn-change-variant').data('item-id', updatedItem.id).attr('data-item-id', updatedItem.id);
                                $row.find('.btn-qty-minus, .btn-qty-plus').data('id', updatedItem.id).attr('data-id', updatedItem.id);
                            }

                            $row.find('.item-variant-label').text('Variant: ' + variantNames.join(' / '));
                            $row.find('.checkout-qty-val').text(qty);
                            $row.find('.item-row-total').data('price', variant.price).data('qty', qty)
                                .text(fmtTK(variant.price * qty));
                            $row.find('.item-unit-price span').text(parseFloat(variant.price).toFixed(2));

                            recalcTotals();

                            bootstrap.Modal.getInstance(document.getElementById('variantChangeModal')).hide();
                        }
                    },
                    error: function () { alert('Could not apply changes. Please try again.'); },
                    complete: function () {
                        $('#modal-apply-btn').prop('disabled', false).find('span').text('Apply Changes');
                    }
                });
            });

            /* Init totals */
            recalcTotals();
        });
        </script>
    @endpush
</x-frontend-layout>

