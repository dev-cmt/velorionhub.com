<x-frontend-layout title="Shopping Cart" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="{{ route('home') }}" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Cart</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Shopping Cart -->
    <div class="s-shoping-cart tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar first"></span>
                    <div class="step-payment ">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="{{ route('cart') }}" class="text-secondary body-text-3">Shopping Cart</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="{{ route('checkout') }}" class="link-secondary body-text-3">Shopping & Checkout</a>

                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <a href="javascript:void(0);" class="link-secondary body-text-3">Confirmation</a>
                    </div>
                </div>
            </div>

            @php
                $cart = \Cart::session(Auth::id() ?? session()->getId());
                $items = $cart->getContent()->sortBy('id');
            @endphp

            @if($items->count() > 0)
                <form class="form-discount" onsubmit="event.preventDefault();">
                    <div class="overflow-x-auto">
                        <table class="tf-table-page-cart">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                @php
                                    $product = $item->associatedModel;
                                    $mainImage = $product && $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
                                    $productUrl = $product ? route('product.show', $product->slug) : '#';
                                @endphp
                                <tr class="tf-cart-item" data-id="{{ $item->id }}">
                                    <td class="tf-cart-item_product">
                                        <a href="{{ $productUrl }}" class="img-box">
                                            <img src="{{ $mainImage }}" alt="{{ $item->name }}">
                                        </a>
                                        <div class="cart-info">
                                            <a href="{{ $productUrl }}" class="cart-title body-md-2 fw-semibold link">
                                                {{ $item->name }}
                                            </a>
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
                                                <div class="variant-box mt-1">
                                                    <p class="body-text-3 text-secondary"><strong>Variant:</strong> {{ $variantLine }}</p>
                                                </div>
                                            @elseif($variantLabel)
                                                <div class="variant-box mt-1">
                                                    <p class="body-text-3 text-secondary"><strong>Variant:</strong> {{ $variantLabel }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td data-cart-title="Price" class="tf-cart-item_price ">
                                        <p class="cart-price price-text fw-medium">TK {{ number_format($item->price, 2) }}</p>
                                    </td>
                                    <td data-cart-title="Quantity" class="tf-cart-item_quantity">
                                        <div class="wg-quantity">
                                            <span class="btn-quantity btn-decrease-cart">
                                                <i class="icon-minus"></i>
                                            </span>
                                            <input class="quantity-product-cart" type="text" value="{{ $item->quantity }}" data-id="{{ $item->id }}" readonly>
                                            <span class="btn-quantity btn-increase-cart">
                                                <i class="icon-plus"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td data-cart-title="Total" class="tf-cart-item_total">
                                        <p class="cart-total total-price price-text fw-medium">TK {{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </td>
                                    <td data-cart-title="Remove" class="remove-cart text-xxl-end">
                                        <span class="remove icon icon-close link" data-id="{{ $item->id }}"></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-bottom">
                        <div class="ip-discount-code">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="button" class="tf-btn btn-gray">
                                <span class="text-white">Apply coupon</span>
                            </button>
                        </div>
                        <span class="last-total-price main-title fw-semibold">Total: TK {{ number_format($cart->getTotal(), 2) }}</span>
                    </div>
                </form>
                <div class="box-btn">
                    <a href="{{ route('shop') }}" class="tf-btn btn-gray"><span class="text-white">Continue shopping</span></a>
                    <a href="{{ route('checkout') }}" class="tf-btn"><span class="text-white">Proceed to checkout</span></a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="icon icon-shop-cart-1" style="font-size: 80px; color: #ccc;"></i>
                    </div>
                    <h3 class="fw-semibold">Your cart is empty</h3>
                    <p class="text-muted my-3">Add some items to your cart to get started.</p>
                    <a href="{{ route('shop') }}" class="tf-btn"><span class="text-white">Return to Shop</span></a>
                </div>
            @endif

        </div>
    </div>
    @push('js')
    <script>
        $(document).ready(function() {
            $('.btn-increase-cart').click(function() {
                let input = $(this).siblings('.quantity-product-cart');
                let val = parseInt(input.val()) + 1;
                input.val(val).trigger('change');
            });

            $('.btn-decrease-cart').click(function() {
                let input = $(this).siblings('.quantity-product-cart');
                let val = parseInt(input.val()) - 1;
                if(val >= 1) {
                    input.val(val).trigger('change');
                }
            });

            $('.quantity-product-cart').on('change', function() {
                let id = $(this).data('id');
                let qty = $(this).val();
                $.ajax({
                    url: "{{ route('cart.update.qty') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        qty: qty,
                        quantity: qty
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            $('.remove-cart .remove').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                if (!id) return;
                let url = "{{ route('cart.remove', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
    </script>
    @endpush

        </div>
    </div>
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
