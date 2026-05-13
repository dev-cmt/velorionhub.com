{{-- prodcut-item1.blade.php --}}
<div class="col-xl-4 col-lg-3">
    <div class="product text-center mb-2">

        {{-- Product Media --}}
        <figure class="product-media">
            <a href="{{ route('product.show', $product->slug) }}">
                @php
                    $mainImage = $product->main_image ? asset($product->main_image) : asset('images/default-product.jpg');
                    $galleryImage = $product->media->first()?->path ? asset($product->media->first()->path) : $mainImage;
                @endphp

                <img src="{{ $mainImage }}" alt="{{ $product->name }}" width="300" height="338">
                <img src="{{ $galleryImage }}" alt="{{ $product->name }}" width="300" height="338">
            </a>

            {{-- Action Buttons --}}
            <div class="product-action-vertical">
                <a href="#" class="btn-product-icon btn-cart w-icon-cart" title="Add to cart"></a>
                <a href="#" class="btn-product-icon btn-wishlist w-icon-heart" title="Add to wishlist"></a>
                <a href="#" class="btn-product-icon btn-quickview w-icon-search" title="Quickview"></a>
                <a href="#" class="btn-product-icon btn-compare w-icon-compare" title="Add to Compare"></a>
            </div>
        </figure>

        {{-- Product Details --}}
        <div class="product-details">
            <h4 class="product-name">
                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
            </h4>

            {{-- Ratings --}}
            <div class="ratings-container">
                <div class="ratings-full">
                    @php
                        $ratingPercent = ($product->reviews_avg_rating ?? 0) * 20; // 5-star to %
                    @endphp
                    <span class="ratings" style="width: {{ $ratingPercent }}%;"></span>
                    <span class="tooltiptext tooltip-top"></span>
                </div>
                <a href="{{ route('product.show', $product->slug) }}" class="rating-reviews">
                    ({{ $product->reviews_count ?? 0 }} Reviews)
                </a>
            </div>

            {{-- Price --}}
            <div class="product-price">
                @if($product->sale_price && $product->sale_price < $product->regular_price)
                    <ins class="new-price">${{ number_format($product->sale_price, 2) }}</ins>
                    <del class="old-price">${{ number_format($product->regular_price, 2) }}</del>
                @else
                    <ins class="new-price">${{ number_format($product->regular_price, 2) }}</ins>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- End of prodcut-item1.blade.php --}}
