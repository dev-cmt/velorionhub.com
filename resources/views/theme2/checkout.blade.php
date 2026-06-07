<x-frontend-layout title="Checkout" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                    <h1 class="block-header__title">Checkout</h1>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            @php
                $cart = \Cart::session(Auth::id() ?? session()->getId());
                $items = $cart->getContent();
            @endphp
            
            @if($items->count() > 0)
            <form action="{{ route('place.order') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row">
                    <!-- Billing Details Column -->
                    <div class="col-12 col-lg-7">
                        <div class="checkout-card mb-4">
                            <h3 class="checkout-section-title">Billing Details</h3>
                            
                            @if(session('error'))
                                <div class="alert alert-danger" style="border-radius: 12px;">{{ session('error') }}</div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="premium-form-group">
                                        <label for="checkout-first-name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="premium-form-control" id="checkout-first-name" name="first_name" required placeholder="John">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="premium-form-group">
                                        <label for="checkout-last-name">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="premium-form-control" id="checkout-last-name" name="last_name" required placeholder="Doe">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="premium-form-group">
                                        <label for="checkout-phone">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="premium-form-control" id="checkout-phone" name="phone" required placeholder="+1 (555) 000-0000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="premium-form-group">
                                        <label for="checkout-email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="premium-form-control" id="checkout-email" name="email" required placeholder="john@example.com">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="premium-form-group">
                                <label for="checkout-address">Street Address <span class="text-danger">*</span></label>
                                <input type="text" class="premium-form-control" id="checkout-address" name="address" required placeholder="House number and street name">
                            </div>
                            
                            <div class="premium-form-group">
                                <label for="checkout-city">Town / City <span class="text-danger">*</span></label>
                                <input type="text" class="premium-form-control" id="checkout-city" name="city" required placeholder="New York">
                            </div>
                            
                            <div class="premium-form-group mb-0">
                                <label for="checkout-comment">Order Notes <span class="text-muted font-weight-normal">(Optional)</span></label>
                                <textarea id="checkout-comment" class="premium-form-control" name="note" rows="4" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary Column -->
                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                        <div class="checkout-card order-summary-card">
                            <h3 class="checkout-section-title">Your Order</h3>
                            
                            <div class="order-items-list">
                                @foreach($items as $item)
                                <div class="order-item-row">
                                    <div class="order-item-details">
                                        <div class="order-item-name">{{ $item->name }}</div>
                                        <div class="order-item-qty">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="order-item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="order-subtotals">
                                <div class="order-subtotal-row">
                                    <span>Subtotal</span>
                                    <span class="font-weight-bold" style="color: #1a1a1a;">${{ number_format($cart->getSubTotal(), 2) }}</span>
                                </div>
                                <div class="order-subtotal-row">
                                    <span>Shipping</span>
                                    <span class="text-success font-weight-bold">Free Shipping</span>
                                </div>
                            </div>
                            
                            <div class="order-total-row">
                                <span>Total</span>
                                <span class="order-total-price">${{ number_format($cart->getTotal(), 2) }}</span>
                            </div>
                            
                            <div class="payment-methods-box">
                                <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 15px;">Payment Method</h4>
                                <div class="payment-option active">
                                    <label class="d-flex align-items-center mb-0 cursor-pointer w-100">
                                        <input type="radio" name="payment_method" value="cod" checked class="mr-3 custom-radio">
                                        <span class="font-weight-bold">Cash on Delivery</span>
                                        <i class="fas fa-truck ml-auto text-muted"></i>
                                    </label>
                                    <div class="payment-desc">
                                        Pay with cash upon delivery. Safe and secure.
                                    </div>
                                </div>
                                
                                <div class="payment-option">
                                    <label class="d-flex align-items-center mb-0 cursor-pointer w-100">
                                        <input type="radio" name="payment_method" value="cash" class="mr-3 custom-radio">
                                        <span class="font-weight-bold">Cash Pickup</span>
                                        <i class="fas fa-store ml-auto text-muted"></i>
                                    </label>
                                    <div class="payment-desc" style="display: none;">
                                        Pay with cash when you pick up your order at our store.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="terms-check premium-form-group">
                                <label class="d-flex align-items-start cursor-pointer">
                                    <input type="checkbox" id="checkout-terms" required class="mt-1 mr-2 custom-checkbox">
                                    <span class="text-muted small">I have read and agree to the website <a href="#" style="color: #e53935;">terms and conditions</a> <span class="text-danger">*</span></span>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn-place-order">
                                Place Order <i class="fas fa-lock ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @else
                <div class="text-center pb-5 pt-5" style="background: #fff; border-radius: 16px; padding: 60px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                    <div style="font-size: 60px; color: #ddd; margin-bottom: 20px;"><i class="fas fa-shopping-cart"></i></div>
                    <h3 class="mb-4" style="font-weight: 700;">Your shopping cart is empty</h3>
                    <a href="{{ url('/') }}" class="btn-place-order d-inline-block" style="width: auto; padding: 12px 35px; border-radius: 30px; text-decoration: none;">Continue Shopping</a>
                </div>
            @endif
        </div>
    </div>
    
    @push('css')
    <style>
        /* Modern Checkout UI */
        .premium-checkout-container {
            padding: 60px 0;
            background: #fdfdfd;
        }
        .checkout-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0,0,0,0.02);
            padding: 35px;
        }
        .checkout-section-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1a1a1a;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 12px;
        }
        
        /* Form Inputs */
        .premium-form-group {
            margin-bottom: 22px;
        }
        .premium-form-group label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .premium-form-control {
            width: 100%;
            padding: 14px 18px;
            font-size: 15px;
            color: #333;
            background-color: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .premium-form-control:focus {
            background-color: #fff;
            border-color: #e53935;
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.1);
            outline: none;
        }
        .premium-form-control::placeholder {
            color: #aaa;
        }
        
        /* Order Summary */
        .order-summary-card {
            position: sticky;
            top: 100px;
            background: linear-gradient(to bottom, #ffffff, #fafafa);
        }
        .order-items-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
            margin-bottom: 20px;
        }
        .order-items-list::-webkit-scrollbar {
            width: 6px;
        }
        .order-items-list::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 10px;
        }
        .order-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed #eee;
        }
        .order-item-row:last-child {
            border-bottom: none;
        }
        .order-item-name {
            font-weight: 600;
            color: #333;
            font-size: 15px;
            margin-bottom: 4px;
        }
        .order-item-qty {
            font-size: 13px;
            color: #888;
        }
        .order-item-price {
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .order-subtotals {
            border-top: 2px solid #f0f0f0;
            border-bottom: 2px solid #f0f0f0;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .order-subtotal-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #555;
            font-size: 15px;
        }
        .order-subtotal-row:last-child {
            margin-bottom: 0;
        }
        
        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .order-total-row span:first-child {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        .order-total-price {
            font-size: 28px;
            font-weight: 800;
            color: #e53935;
        }
        
        /* Payment Methods */
        .payment-methods-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #eee;
        }
        .payment-option {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .payment-option:last-child {
            margin-bottom: 0;
        }
        .payment-option.active {
            background: #fff;
            border-color: #e53935;
            box-shadow: 0 4px 10px rgba(229, 57, 53, 0.08);
        }
        .custom-radio {
            width: 18px;
            height: 18px;
            accent-color: #e53935;
        }
        .payment-desc {
            font-size: 13px;
            color: #666;
            margin-top: 8px;
            padding-left: 30px;
        }
        
        /* Checkbox */
        .custom-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #e53935;
            margin-top: 3px;
        }
        
        /* Button */
        .btn-place-order {
            background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 18px;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            text-align: center;
            display: block;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
        }
        .btn-place-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(229, 57, 53, 0.4);
        }
        
        @media (max-width: 991px) {
            .checkout-card {
                padding: 25px;
            }
        }
    </style>
    @endpush
    
    @push('js')
    <script>
        $(document).ready(function() {
            // Handle payment method toggling
            $('input[name="payment_method"]').on('change', function() {
                $('.payment-option').removeClass('active');
                $('.payment-desc').slideUp(200);
                
                let parent = $(this).closest('.payment-option');
                parent.addClass('active');
                parent.find('.payment-desc').slideDown(200);
            });
        });
    </script>
    @endpush
</x-frontend-layout>
