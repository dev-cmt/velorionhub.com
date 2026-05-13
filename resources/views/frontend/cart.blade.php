<x-frontend-layout title="Shopping Cart" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb">
            <div class="container">
                <div class="block-header__body">
                    <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb__list">
                            @foreach($breadcrumb_list as $breadcrumb)
                                <li class="breadcrumb__item @if($loop->first) breadcrumb__item--parent breadcrumb__item--first @endif @if($loop->last) breadcrumb__item--current @endif">
                                    @if(!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                    @else
                                        <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                    <h1 class="block-header__title">Shopping Cart</h1>
                </div>
            </div>
        </div>

        <div class="container mb-4">
            @php
                $cart = \Cart::session(Auth::id() ?? session()->getId());
                $items = $cart->getContent()->sortBy('id');
            @endphp
            
            @if($items->count() > 0)
                <div class="row">
                    <!-- Cart Items Column -->
                    <div class="col-lg-8">
                        <div class="cart-card">
                            <div class="cart-header d-none d-md-flex justify-content-between">
                                <span style="width: 50%;">Product Details</span>
                                <span style="width: 15%; text-align: center;">Price</span>
                                <span style="width: 20%; text-align: center;">Quantity</span>
                                <span style="width: 15%; text-align: right;">Total</span>
                            </div>
                            
                            @foreach($items as $item)
                            <div class="cart-item-row" data-id="{{ $item->id }}">
                                <div class="d-flex align-items-center" style="width: 100%; max-width: 50%;">
                                    <a href="{{ $item->attributes->url ?? '#' }}">
                                        <img src="{{ $item->attributes->image ?? asset('images/no-image.jpg') }}" class="cart-item-img" alt="{{ $item->name }}">
                                    </a>
                                    <div class="cart-item-info ml-4">
                                        <a href="{{ $item->attributes->url ?? '#' }}" class="cart-item-title">{{ $item->name }}</a>
                                        <div class="text-muted small mt-1">Item ID: {{ $item->id }}</div>
                                        @if(isset($item->attributes->attributes) && is_array($item->attributes->attributes))
                                        <ul class="list-unstyled mt-2 small text-secondary">
                                            @foreach($item->attributes->attributes as $key => $val)
                                                <li><strong>{{ ucfirst($key) }}:</strong> {{ $val }}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="cart-item-price d-none d-md-block" style="width: 15%; text-align: center;">
                                    TK {{ number_format($item->price, 2) }}
                                </div>
                                
                                <div style="width: 20%; text-align: center;" class="d-flex justify-content-center">
                                    <div class="cart-qty-wrapper">
                                        <button type="button" class="btn btn-sm btn-light p-0 border-0 cart-qty-minus" data-id="{{ $item->id }}" style="background: transparent; color: #555;"><i class="fas fa-minus"></i></button>
                                        <input class="cart-qty-input cart-update-qty mx-2" type="number" min="1" value="{{ $item->quantity }}" data-id="{{ $item->id }}" readonly>
                                        <button type="button" class="btn btn-sm btn-light p-0 border-0 cart-qty-plus" data-id="{{ $item->id }}" style="background: transparent; color: #555;"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                
                                <div class="cart-item-total d-none d-md-block" style="width: 15%;">
                                    TK {{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                                
                                <button type="button" class="btn-remove-item remove-cart ml-auto" data-id="{{ $item->id }}" title="Remove item">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ url('/') }}" class="btn-continue"><i class="fas fa-arrow-left mr-2"></i> Continue Shopping</a>
                        </div>
                    </div>
                    
                    <!-- Summary Column -->
                    <div class="col-lg-4 mt-5 mt-lg-0">
                        <div class="summary-card">
                            <h3 class="summary-title">Order Summary</h3>
                            
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span class="font-weight-bold" style="color: #1a1a1a;">TK {{ number_format($cart->getSubTotal(), 2) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Shipping Estimate</span>
                                <span class="text-success font-weight-bold">Free</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Tax</span>
                                <span>Calculated at checkout</span>
                            </div>
                            
                            <div class="summary-total">
                                <span>Total</span>
                                <span style="color: #e53935;">TK {{ number_format($cart->getTotal(), 2) }}</span>
                            </div>
                            
                            <a href="{{ route('checkout') }}" class="btn-premium mt-4">Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i></a>
                            
                            <div class="mt-4 text-center text-muted small">
                                <i class="fas fa-lock mr-1"></i> Secure checkout powered by Stripe
                            </div>
                            <div class="mt-3 text-center">
                                <img src="{{ asset('frontend/images/payments.png') }}" alt="Secure Payments" style="max-height: 25px; opacity: 0.6;">
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart-state">
                    <div class="empty-cart-icon"><i class="fas fa-shopping-basket"></i></div>
                    <h2 class="empty-cart-title">Your cart is currently empty.</h2>
                    <p class="empty-cart-desc">Before proceed to checkout you must add some products to your shopping cart.<br>You will find a lot of interesting products on our "Shop" page.</p>
                    <a href="{{ url('/') }}" class="btn-premium d-inline-block" style="width: auto; padding: 14px 40px;">Return to Shop</a>
                </div>
            @endif
        </div>
    </div>
    
    @push('css')
    <style>
        /* Modern Cart UI */
        .cart-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0,0,0,0.02);
            overflow: hidden;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }
        .cart-card:hover {
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
        }
        .cart-header {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 30px;
            font-weight: 600;
            color: #333;
            letter-spacing: 0.5px;
        }
        .cart-item-row {
            padding: 25px 30px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }
        .cart-item-row:last-child {
            border-bottom: none;
        }
        .cart-item-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .cart-item-info {
            flex-grow: 1;
        }
        .cart-item-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            text-decoration: none;
            margin-bottom: 6px;
            display: block;
            transition: color 0.2s;
        }
        .cart-item-title:hover {
            color: #e53935;
        }
        .cart-item-price {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        .cart-qty-wrapper {
            display: flex;
            align-items: center;
            background: #f8f8f8;
            border-radius: 30px;
            padding: 4px 12px;
            border: 1px solid #eee;
        }
        .cart-qty-input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
        }
        .cart-qty-input:focus {
            outline: none;
        }
        .cart-item-total {
            font-size: 18px;
            font-weight: 700;
            color: #e53935;
            min-width: 100px;
            text-align: right;
        }
        .btn-remove-item {
            background: #fff0f0;
            color: #ff4d4f;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-remove-item:hover {
            background: #ff4d4f;
            color: #fff;
            transform: rotate(90deg);
        }
        .summary-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.04);
            padding: 30px;
            position: sticky;
            top: 100px;
        }
        .summary-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #1a1a1a;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 16px;
            color: #555;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #eee;
            font-size: 24px;
            font-weight: 800;
            color: #1a1a1a;
        }
        .btn-premium {
            background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);
            color: white !important;
            border: none;
            border-radius: 30px;
            padding: 16px 32px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            text-align: center;
            display: block;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
        }
        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(229, 57, 53, 0.4);
            text-decoration: none;
        }
        .empty-cart-state {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-cart-icon {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }
        .empty-cart-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .empty-cart-desc {
            color: #777;
            font-size: 16px;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-continue {
            background: #fff;
            color: #333;
            border: 2px solid #eee;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-continue:hover {
            background: #f5f5f5;
            border-color: #ddd;
            text-decoration: none;
            color: #1a1a1a;
        }
        @media (max-width: 768px) {
            .cart-item-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .cart-item-row > div:first-child {
                max-width: 100% !important;
                margin-bottom: 15px;
            }
            .btn-remove-item {
                position: absolute;
                right: 20px;
                top: 20px;
            }
            .cart-item-total {
                display: block !important;
                text-align: left !important;
                margin-top: 15px;
                width: 100% !important;
            }
            .cart-item-price {
                display: block !important;
                text-align: left !important;
                margin-bottom: 10px;
                width: 100% !important;
            }
            .cart-qty-wrapper {
                width: max-content;
            }
        }
    </style>
    @endpush
    
    @push('js')
    <script>
        $(document).ready(function() {
            // Update quantity with custom buttons
            $('.cart-qty-plus').click(function() {
                let input = $(this).siblings('.cart-qty-input');
                let val = parseInt(input.val()) + 1;
                input.val(val).trigger('change');
            });
            
            $('.cart-qty-minus').click(function() {
                let input = $(this).siblings('.cart-qty-input');
                let val = parseInt(input.val()) - 1;
                if(val >= 1) {
                    input.val(val).trigger('change');
                }
            });
            
            $('.cart-qty-input').on('change', function() {
                let id = $(this).data('id');
                let qty = $(this).val();
                $.ajax({
                    url: "{{ route('cart.update.qty') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        qty: qty
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });


        });
    </script>
    @endpush
</x-frontend-layout>