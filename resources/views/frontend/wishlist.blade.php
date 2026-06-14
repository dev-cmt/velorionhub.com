<x-frontend-layout title="Wishlist" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                    <span class="body-small"> Wishlist</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Wishlist -->
    @php
        $wishlistItems = \Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->getContent();
    @endphp
    <div class="tf-sp-2">
        <div class="container">
            <div class="tf-wishlist">
                <table class="tf-table-wishlist">
                    <thead>
                        <tr>
                            <th class="wishlist-item_remove"></th>
                            <th class="wishlist-item_image"></th>
                            <th class="wishlist-item_info">
                                <p class="product-title fw-semibold">Product Name</p>
                            </th>
                            <th class="wishlist-item_price">
                                <p class="product-title fw-semibold">Unit Price</p>
                            </th>
                            <th class="wishlist-item_stock">
                                <p class="product-title fw-semibold">Stock Status</p>
                            </th>
                            <th class="wishlist-item_action"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wishlistItems as $item)
                        @php
                            $product = \App\Models\Product::find($item->id);
                            $hasVariant = $product && $product->has_variant ? '1' : '0';

                            $inCart = false;
                            $cartItemId = null;
                            try {
                                $cartItems = \Cart::session(Auth::id() ?? session()->getId())->getContent();
                                foreach ($cartItems as $cItem) {
                                    if (($cItem->attributes->product_id ?? null) == $item->id) {
                                        $inCart = true;
                                        $cartItemId = $cItem->id;
                                        break;
                                    }
                                }
                            } catch (\Exception $e) {}
                        @endphp
                        <tr class="wishlist-item" data-id="{{ $item->id }}">
                            <td class="wishlist-item_remove">
                                <i class="icon-close remove link cs-pointer" data-id="{{ $item->id }}"></i>
                            </td>
                            <td class="wishlist-item_image">
                                <a href="{{ $item->attributes->url ?? '#' }}">
                                    <img src="{{ $item->attributes->image ?? asset('images/no-image.jpg') }}"
                                        data-src="{{ $item->attributes->image ?? asset('images/no-image.jpg') }}"
                                        alt="{{ $item->name }}" class="lazyload">
                                </a>
                            </td>
                            <td class="wishlist-item_info">
                                <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                    href="{{ $item->attributes->url ?? '#' }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="wishlist-item_price">
                                <p class="price-wrap fw-medium flex-nowrap">
                                    <span class="new-price price-text fw-medium mb-0">TK {{ number_format($item->price, 2) }}</span>
                                </p>
                            </td>
                            <td class="wishlist-item_stock">
                                <span class="wishlist-stock-status">{{ $item->attributes->stock ?? 'N/A' }}</span>
                            </td>
                            <td class="wishlist-item_action">
                                <button type="button"
                                    class="tf-btn btn-gray add-to-cart {{ $inCart ? 'in-cart active' : '' }}"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-price="{{ $item->price }}"
                                    data-image="{{ $item->attributes->image ?? asset('images/no-image.jpg') }}"
                                    data-url="{{ $item->attributes->url ?? '#' }}"
                                    data-has-variant="{{ $hasVariant }}"
                                    data-product-url="{{ $item->attributes->url ?? '#' }}"
                                    @if($inCart) data-cart-id="{{ $cartItemId }}" @endif>
                                    <span class="text-white">{{ $inCart ? 'Remove from Cart' : 'Add To Cart' }}</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tfoot @if($wishlistItems->count() > 0) class="d-none" @endif>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-4">
                                    <i class="icon-heart2" style="font-size: 80px; color: #ccc;"></i>
                                </div>
                                <h3 class="fw-semibold">Your wishlist is empty</h3>
                                <p class="text-muted my-3">Save your favourite items here to buy them later.</p>
                                <a href="{{ route('shop') }}" class="tf-btn"><span class="text-white">Continue Shopping</span></a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- /Wishlist -->
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
