@if($products->count() > 0)
    <div class="suggestion-header">
        <span>{{ $products->count() }} result{{ $products->count() > 1 ? 's' : '' }} found</span>
        <a href="{{ route('shop', ['search' => $query ?? '']) }}" class="suggestion-view-all">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @foreach($products as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="search-suggestion-item">
            <div class="suggestion-img-wrap">
                <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}"
                     alt="{{ $product->name }}"
                     loading="lazy">
            </div>
            <div class="search-suggestion-info">
                <span class="search-suggestion-name">{{ Str::limit($product->name, 55) }}</span>
                <div class="suggestion-price-row">
                    <span class="search-suggestion-price">TK {{ number_format($product->sale_price, 2) }}</span>
                    @if($product->sale_price < $product->regular_price)
                        <del class="suggestion-old-price">TK {{ number_format($product->regular_price, 2) }}</del>
                        @php
                            $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                        @endphp
                        <span class="suggestion-badge">-{{ $discount }}%</span>
                    @endif
                </div>
            </div>
            <span class="suggestion-arrow"><i class="fas fa-chevron-right"></i></span>
        </a>
    @endforeach
@else
    <div class="suggestion-empty">
        <i class="fas fa-search suggestion-empty-icon"></i>
        <p>No products found</p>
        <span>Try a different keyword</span>
    </div>
@endif
