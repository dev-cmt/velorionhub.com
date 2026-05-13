<x-frontend-layout title="Order Confirmation">
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
                </div>
            </div>
        </div>
        
        <div class="block-space block-space--layout--spaceship-ledge-height"></div>
        
        <div class="block order-success">
            <div class="container">
                <div class="order-success__body">
                    <div class="order-success__header">
                        <svg class="order-success__icon" width="100" height="100">
                            <path d="M50,100C22.4,100,0,77.6,0,50S22.4,0,50,0s50,22.4,50,50S77.6,100,50,100z M50,4C24.6,4,4,24.6,4,50s20.6,46,46,46s46-20.6,46-46S75.4,4,50,4z M71.6,30.7L44.7,57.7l-16.4-16.4L25,44.5l19.7,19.7l30.1-30.1L71.6,30.7z" />
                        </svg>
                        <h1 class="order-success__title">Thank you</h1>
                        <div class="order-success__subtitle">Your order has been received</div>
                        <div class="order-success__actions">
                            <a href="{{ url('/') }}" class="btn-premium">Go To Homepage <i class="fas fa-home ml-2"></i></a>
                        </div>
                    </div>
                    
                    <div class="card order-success__meta card-premium">
                        <ul class="order-success__meta-list">
                            <li class="order-success__meta-item">
                                <span class="order-success__meta-title">Order number:</span>
                                <span class="order-success__meta-value">#{{ $order->invoice_no }}</span>
                            </li>
                            <li class="order-success__meta-item">
                                <span class="order-success__meta-title">Date:</span>
                                <span class="order-success__meta-value">{{ $order->created_at->format('M d, Y') }}</span>
                            </li>
                            <li class="order-success__meta-item">
                                <span class="order-success__meta-title">Total:</span>
                                <span class="order-success__meta-value">TK {{ number_format($order->total, 2) }}</span>
                            </li>
                            <li class="order-success__meta-item">
                                <span class="order-success__meta-title">Payment method:</span>
                                <span class="order-success__meta-value">{{ $order->payment_method == 0 ? 'Cash' : 'Cash on Delivery' }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card card-premium">
                        <div class="card-body card-body--padding--2">
                            <h3 class="card-title">Order Details</h3>
                            <table class="order-success__table">
                                <thead>
                                    <tr>
                                        <th class="order-success__column-product">Product</th>
                                        <th class="order-success__column-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="order-success__column-product">
                                            {{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}
                                        </td>
                                        <td class="order-success__column-total">
                                            TK {{ number_format($item->sale_price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tbody class="order-success__subtotals">
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>TK {{ number_format($order->sub_total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping</th>
                                        <td>Free</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <td>TK {{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
</x-frontend-layout>
