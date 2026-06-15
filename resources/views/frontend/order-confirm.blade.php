<x-frontend-layout title="Order Confirmation">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ url('/') }}" class="body-small link">Home</a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">Order Confirmation</span>
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
                    <span class="checkout-bar end"></span>
                    <div class="step-payment ">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="{{ route('cart') }}" class="link-secondary body-text-3">Shopping Cart</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="{{ route('checkout') }}" class="link-secondary body-text-3">Shopping &amp; Checkout</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <a href="#" class="text-secondary body-text-3">Confirmation</a>
                    </div>
                </div>
            </div>

            @php
                $paymentMethods = [
                    0 => 'Cash',
                    1 => 'Card',
                    2 => 'Mobile Banking',
                    3 => 'Cash on Delivery (COD)',
                    4 => 'Bank Transfer',
                ];
                $paymentMethodLabel = $paymentMethods[$order->payment_method] ?? 'COD';

                $orderStatuses = [
                    0 => 'Pending',
                    1 => 'Confirmed',
                    2 => 'Hold',
                    3 => 'Cancelled',
                    4 => 'Delivered',
                ];
                $statusLabel = $orderStatuses[$order->status] ?? 'Pending';
            @endphp

            <div class="tf-order-detail">
                <div class="order-notice">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#ffffff" viewBox="0 0 256 256">
                            <path d="M225.86,102.82c-3.77-3.94-7.67-8-9.14-11.57-1.36-3.27-1.44-8.69-1.52-13.94-.15-9.76-.31-20.82-8-28.51s-18.75-7.85-28.51-8c-5.25-.08-10.67-.16-13.94-1.52-3.56-1.47-7.63-5.37-11.57-9.14C146.28,23.51,138.44,16,128,16s-18.27,7.51-25.18,14.14c-3.94,3.77-8,7.67-11.57,9.14C88,40.64,82.56,40.72,77.31,40.8c-9.76.15-20.82.31-28.51,8S41,67.55,40.8,77.31c-.08,5.25-.16,10.67-1.52,13.94-1.47,3.56-5.37,7.63-9.14,11.57C23.51,109.72,16,117.56,16,128s7.51,18.27,14.14,25.18c3.77,3.94,7.67,8,9.14,11.57,1.36,3.27,1.44,8.69,1.52,13.94.15,9.76.31,20.82,8,28.51s18.75,7.85,28.51,8c5.25.08,10.67.16,13.94,1.52,3.56,1.47,7.63,5.37,11.57,9.14C109.72,232.49,117.56,240,128,240s18.27-7.51,25.18-14.14c3.94-3.77,8-7.67,11.57-9.14,3.27-1.36,8.69-1.44,13.94-1.52,9.76-.15,20.82-.31,28.51-8s7.85-18.75,8-28.51c.08-5.25.16-10.67,1.52-13.94,1.47-3.56,5.37-7.63,9.14-11.57C232.49,146.28,240,138.44,240,128S232.49,109.73,225.86,102.82Zm-11.55,39.29c-4.79,5-9.75,10.17-12.38,16.52-2.52,6.1-2.63,13.07-2.73,19.82-.1,7-.21,14.33-3.32,17.43s-10.39,3.22-17.43,3.32c-6.75.1-13.72.21-19.82,2.73-6.35,2.63-11.52,7.59-16.52,12.38S132,224,128,224s-9.15-4.92-14.11-9.69-10.17-9.75-16.52-12.38c-6.1-2.52-13.07-2.63-19.82-2.73-7-.1-14.33-.21-17.43-3.32s-3.22-10.39-3.32-17.43c-.1-6.75-.21-13.72-2.73-19.82-2.63-6.35-7.59-11.52-12.38-16.52S32,132,32,128s4.92-9.15,9.69-14.11,9.75-10.17,12.38-16.52c2.52-6.1,2.63-13.07,2.73-19.82.1-7,.21-14.33,3.32-17.43S70.51,56.9,77.55,56.8c6.75-.1,13.72-.21,19.82-2.73,6.35-2.63,11.52-7.59,16.52-12.38S124,32,128,32s9.15,4.92,14.11,9.69,10.17,9.75,16.52,12.38c6.1,2.52,13.07,2.63,19.82,2.73,7,.1,14.33.21,17.43,3.32s3.22,10.39,3.32,17.43c.1,6.75.21,13.72,2.73,19.82,2.63,6.35,7.59,11.52,12.38,16.52S224,124,224,128,219.08,137.15,214.31,142.11ZM173.66,98.34a8,8,0,0,1,0,11.32l-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35A8,8,0,0,1,173.66,98.34Z">
                            </path>
                        </svg>
                    </span>
                    <p>Thank you. Your order has been received.</p>
                </div>

                <ul class="order-overview-list">
                    <li>Order number: <strong>{{ $order->invoice_no }}</strong></li>
                    <li>Date: <strong>{{ $order->created_at->format('F j, Y') }}</strong></li>
                    <li>Total: <strong>TK {{ number_format($order->total, 2) }}</strong></li>
                    <li>Payment method: <strong>{{ $paymentMethodLabel }}</strong></li>
                    <li>Status: <strong>{{ $statusLabel }}</strong></li>
                </ul>

                <div class="order-detail-wrap">
                    <h5 class="fw-bold">Order details</h5>
                    <table class="tf-table-order-detail">
                        <thead>
                            <tr>
                                <td>
                                    <h6 class="fw-semibold">Product</h6>
                                </td>
                                <td>
                                    <h6 class="fw-semibold">Total</h6>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="tf-order-item">
                                    <td class="tf-order-item_product">
                                        @if($item->product)
                                            <a href="{{ route('product.show', $item->product->slug) }}" class="link fw-normal">
                                                {{ $item->product->name }}
                                                @if(!empty($item->attributes))
                                                    @php
                                                        $attributes = $item->attributes;
                                                        if (is_string($attributes)) {
                                                            $attributes = json_decode($attributes, true);
                                                        }
                                                        $attrLabels = [];
                                                        if(is_array($attributes)) {
                                                            foreach($attributes as $key => $val) {
                                                                if(!is_numeric($key) && !in_array($key, ['image', 'url', 'variant_label', 'has_variant', 'product_url', 'variant_attributes', 'product_id', 'variant_id', 'variant_key'])) {
                                                                    if (is_scalar($val)) {
                                                                        $attrLabels[] = ucfirst($key) . ': ' . $val;
                                                                    }
                                                                }
                                                            }
                                                            if (empty($attrLabels)) {
                                                                if (!empty($attributes['variant_label'])) {
                                                                    $attrLabels[] = $attributes['variant_label'];
                                                                } elseif (!empty($attributes['variant_attributes']) && is_array($attributes['variant_attributes'])) {
                                                                    foreach ($attributes['variant_attributes'] as $k => $v) {
                                                                        if (is_scalar($v)) {
                                                                            $attrLabels[] = ucfirst($k) . ': ' . $v;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if(!empty($attrLabels))
                                                        <span class="text-muted d-block small">{{ implode(' | ', $attrLabels) }}</span>
                                                    @endif
                                                @endif
                                                <span class="text-black">×{{ $item->quantity }}</span>
                                            </a>
                                        @else
                                            <span class="fw-normal">
                                                {{ $item->sku }}
                                                <span class="text-black">×{{ $item->quantity }}</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-medium">TK {{ number_format($item->sale_price * $item->quantity, 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><span>Subtotal:</span></th>
                                <td><span>TK {{ number_format($order->sub_total, 2) }}</span></td>
                            </tr>
                            @if($order->shipping_cost > 0)
                            <tr>
                                <th><span>Shipping:</span></th>
                                <td><span>TK {{ number_format($order->shipping_cost, 2) }}</span></td>
                            </tr>
                            @else
                            <tr>
                                <th><span>Shipping:</span></th>
                                <td><span>Free shipping</span></td>
                            </tr>
                            @endif
                            @if($order->discount > 0)
                            <tr>
                                <th><span>Discount:</span></th>
                                <td><span>- TK {{ number_format($order->discount, 2) }}</span></td>
                            </tr>
                            @endif
                            <tr>
                                <th><span>Payment method:</span></th>
                                <td><span>{{ $paymentMethodLabel }}</span></td>
                            </tr>
                            <tr>
                                <th>
                                    <p class="fw-semibold product-title text-uppercase">Total:</p>
                                </th>
                                <td><span class="fw-semibold">TK {{ number_format($order->total, 2) }}</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row gap-30 gap-sm-0">
                    <div class="col-sm-6 col-12">
                        <div class="order-detail-wrap">
                            <h5 class="fw-bold">Billing Address</h5>
                            <div class="billing-info">
                                <p>{{ $order->customer_name }}</p>
                                @if($order->customer_address)
                                    <p>{{ $order->customer_address }}</p>
                                @endif
                                @if($order->customer_phone)
                                    <p>{{ $order->customer_phone }}</p>
                                @endif
                                @if($order->customer && $order->customer->email)
                                    <p>{{ $order->customer->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="order-detail-wrap">
                            <h5 class="fw-bold">Order Notes</h5>
                            <div class="billing-info">
                                @if($order->notes)
                                    <p>{{ $order->notes }}</p>
                                @else
                                    <p class="text-muted">No notes provided.</p>
                                @endif
                                @if($order->store)
                                    <p class="mt-2"><strong>Store:</strong> {{ $order->store->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('shop') }}" class="tf-btn btn-gray">
                        <span class="text-white">Continue Shopping</span>
                    </a>
                    <a href="{{ url('/') }}" class="tf-btn mt-2">
                        <span class="text-white">Go to Home</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Check Out Cart -->


    @push('js')
        @if (isset($order))
            <script>
                dataLayer.push({ ecommerce: null });
                dataLayer.push({
                    'event': 'purchase',
                    'action_source': 'website',
                    'order_id': '{{ $order->invoice_no }}',
                    'event_id': '{{ $order->id }}',
                    'ecommerce': {
                        'transaction_id': '{{ $order->invoice_no }}',
                        'affiliation': '{{ $settings->company_name ?? config('app.name') }}',
                        'value': {{ $order->total }},
                        'tax': 0,
                        'shipping': {{ $order->shipping_cost ?? 0 }},
                        'currency': 'BDT',
                        'items': [
                            @foreach ($order->items as $item)
                            {
                                'item_id': '{{ $item->product->id ?? '' }}',
                                'item_name': '{{ $item->product->name ?? '' }}',
                                'item_category': '{{ $item->product->category->name ?? '' }}',
                                'price': {{ $item->sale_price }},
                                'quantity': {{ $item->quantity }}
                            },
                            @endforeach
                        ]
                    },
                    'customer_information': {
                        'first_name': '{{ $order->customer_name ?? '' }}',
                        'last_name': '{{ $order->customer_name ?? '' }}',
                        'phone': '{{ $order->customer_phone ?? '' }}',
                        'address_1': '{{ $order->customer_address ?? '' }}',
                        'city': '{{ $order->customer_address ?? '' }}',
                        'country': 'Bangladesh',
                        'country_code': 'BD'
                    }
                });

                fbq('track', 'Purchase', {
                    content_ids: [@foreach ($order->items as $item) '{{ $item->product->id ?? '' }}', @endforeach],
                    content_type: 'product',
                    value: {{ $order->total }},
                    currency: 'BDT'
                });
            </script>
        @endif
    @endpush
</x-frontend-layout>
