<div class="product-card vh-product-card" id="vh-product-{{ $product->id }}">
    @php
        $attributes = [];
        if($product->has_variant && $product->variants) {
            foreach ($product->variants as $variant) {
                foreach ($variant->variantItems as $item) {
                    if ($item->attribute && $item->attributeItem) {
                        $attributes[$item->attribute->name][$item->attributeItem->id] = $item->attributeItem->name;
                    }
                }
            }
        }
        $hasSale = $product->sale_price < $product->regular_price;
        $discountPercentage = $hasSale ? round(100 - ($product->sale_price / $product->regular_price * 100)) : 0;
    @endphp

    <div class="product-card__image-section">
        <!-- Badges -->
        <div class="vh-product-badges">
            @if($hasSale)
                <span class="vh-badge vh-badge--sale">-{{ $discountPercentage }}%</span>
            @endif
            @if($product->views > 100 || $product->is_featured) {{-- Using views as a proxy for HOT if no specific field --}}
                <span class="vh-badge vh-badge--hot">HOT</span>
            @endif
        </div>

        <!-- Side Actions -->
        <div class="vh-product-actions">
            <button type="button" class="vh-action-btn" onclick="addToCompare({{ $product->id }})" title="Compare">
                <svg width="16" height="16"><path d="M9,15H7c-0.6,0-1-0.4-1-1V2c0-0.6,0.4-1,1-1h2c0.6,0,1,0.4,1,1v12C10,14.6,9.6,15,9,15z M1,9h2c0.6,0,1,0.4,1,1v4c0,0.6-0.4,1-1,1H1c-0.6,0-1-0.4-1-1v-4C0,9.4,0.4,9,1,9z M15,5h-2c-0.6,0-1,0.4-1,1v8c0,0.6,0.4,1,1,1h2c0.6,0,1-0.4,1-1V6C16,5.4,15.6,5,15,5z" /></svg>
            </button>
            <button type="button" class="vh-action-btn" onclick="window.location.href='{{ route('product.show', $product->slug) }}'" title="Quick View">
                <svg width="16" height="16"><path d="M15.9,8.2C15.7,8.1,13.2,4,8,4S0.3,8.1,0.1,8.2C0,8.3,0,8.4,0.1,8.5C0.3,8.6,2.8,12.7,8,12.7s7.7-4.1,7.9-4.2 C16,8.4,16,8.3,15.9,8.2z M8,11.3c-1.8,0-3.3-1.5-3.3-3.3S6.2,4.7,8,4.7s3.3,1.5,3.3,3.3S9.8,11.3,8,11.3z M8,6.2 C6.9,6.2,6,7.1,6,8.2s0.9,2,2,2s2-0.9,2-2S9.1,6.2,8,6.2z" /></svg>
            </button>
            <button type="button" class="vh-action-btn" onclick="addToWishlist({{ $product->id }})" title="Wishlist">
                <svg width="16" height="16"><path d="M13.9,8.4l-5.4,5.4c-0.3,0.3-0.7,0.3-1,0L2.1,8.4c-1.5-1.5-1.5-3.8,0-5.3C2.8,2.4,3.8,2,4.8,2s1.9,0.4,2.6,1.1L8,3.7 l0.6-0.6C9.3,2.4,10.3,2,11.3,2c1,0,1.9,0.4,2.6,1.1C15.4,4.6,15.4,6.9,13.9,8.4z" /></svg>
            </button>
        </div>

        <!-- Image -->
        <a href="{{ route('product.show', $product->slug) }}" class="vh-product-image">
            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}">
        </a>

        <!-- Variant Overlay -->
        @if($product->has_variant && count($attributes) > 0)
        <div class="vh-variant-overlay" id="variant-overlay-{{ $product->id }}">
            <button type="button" class="vh-close-overlay" onclick="toggleVariantOverlay({{ $product->id }})">✕ Close</button>
            <form class="vh-variant-form ms2_form">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="count" value="1">
                
                <div class="vh-overlay-content">
                    @foreach($attributes as $attrName => $items)
                        <div class="vh-attribute-group">
                            <p class="vh-attribute-title">{{ $attrName }}:</p>
                            <div class="vh-attribute-options">
                                @foreach($items as $itemId => $itemName)
                                    <label class="vh-option-label">
                                        <input type="radio" name="attributes[{{ $attrName }}]" value="{{ $itemId }}" class="vh-variant-input" data-product-id="{{ $product->id }}" required>
                                        <span>{{ $itemName }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="vh-overlay-buttons">
                    <button type="submit" class="vh-btn-add-cart-overlay">ADD TO CART</button>
                    <button type="button" class="vh-btn-buy-now-overlay" onclick="buyNow({{ $product->id }})">BUY NOW</button>
                </div>
            </form>
        </div>
        @endif

        <!-- Footer Button -->
        <div class="vh-image-footer">
            @if($product->has_variant)
                <button type="button" class="vh-btn-select" onclick="toggleVariantOverlay({{ $product->id }})">SELECT OPTIONS</button>
            @else
                <form method="post" action="{{ route('cart.add') }}" class="ms2_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="count" value="1">
                    <div class="vh-footer-buttons">
                        <button type="submit" class="vh-btn-select">ADD TO CART</button>
                        <button type="button" class="vh-btn-buy-now" onclick="buyNow({{ $product->id }}, true)">BUY NOW</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="vh-product-info">
        <h3 class="vh-product-name">
            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="vh-product-category">
            {{ $product->category ? $product->category->name : '' }}
            @if($product->brand)
                , {{ $product->brand->name }}
            @endif
        </div>
        <div class="vh-product-rating">
            <div class="rating">
                <div class="rating__body">
                    <div class="rating__best">
                        <div class="rating__current" style="width: {{ ($product->reviews_avg_rating ?? 0) * 20 }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vh-product-price">
            @if($hasSale)
                <span class="vh-price-old">TK {{ number_format($product->regular_price, 2) }}</span>
                <span class="vh-price-new">TK {{ number_format($product->sale_price, 2) }}</span>
            @else
                <span class="vh-price-current">TK {{ number_format($product->sale_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>

<style>
    .vh-product-card {
        position: relative;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 4px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .vh-product-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .product-card__image-section {
        position: relative;
        overflow: hidden;
    }
    .vh-product-image {
        display: block;
        aspect-ratio: 1/1;
        background: #f8f8f8;
    }
    .vh-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .vh-product-card:hover .vh-product-image img {
        transform: scale(1.05);
    }
    
    /* Badges */
    .vh-product-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .vh-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 15px;
        text-transform: uppercase;
        color: #fff;
    }
    .vh-badge--sale { background: #47bd71; }
    .vh-badge--hot { background: #e52727; }

    /* Actions */
    .vh-product-actions {
        position: absolute;
        top: 10px;
        right: -50px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 5px;
        transition: right 0.3s ease;
        background: rgba(255,255,255,0.9);
        border-radius: 4px;
        padding: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .vh-product-card:hover .vh-product-actions {
        right: 10px;
    }
    .vh-action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        cursor: pointer;
        color: #333;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .vh-action-btn:hover {
        background: #47bd71;
        color: #fff;
    }
    .vh-action-btn svg { fill: currentColor; }

    /* Variant Overlay */
    .vh-variant-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.95);
        z-index: 5;
        display: none;
        flex-direction: column;
        padding: 15px;
        box-sizing: border-box;
    }
    .vh-close-overlay {
        align-self: flex-end;
        background: none;
        border: none;
        font-size: 12px;
        cursor: pointer;
        color: #666;
        margin-bottom: 20px;
    }
    .vh-close-overlay:hover { color: #000; }
    
    .vh-overlay-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .vh-attribute-group {
        margin-bottom: 15px;
        width: 100%;
    }
    .vh-attribute-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 10px;
        color: #333;
    }
    .vh-attribute-options {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        justify-content: center;
    }
    .vh-option-label {
        cursor: pointer;
    }
    .vh-option-label input {
        display: none;
    }
    .vh-option-label span {
        display: block;
        padding: 5px 12px;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 13px;
        transition: all 0.2s;
    }
    .vh-option-label input:checked + span {
        border-color: #47bd71;
        background: #47bd71;
        color: #fff;
    }

    .vh-overlay-buttons {
        display: flex;
        gap: 10px;
    }
    .vh-btn-add-cart-overlay, .vh-btn-buy-now-overlay {
        flex: 1;
        color: #fff;
        border: none;
        padding: 12px;
        font-weight: 700;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .vh-btn-add-cart-overlay { background: #47bd71; }
    .vh-btn-add-cart-overlay:hover { background: #3aa862; }
    .vh-btn-buy-now-overlay { background: #333; }
    .vh-btn-buy-now-overlay:hover { background: #000; }

    /* Image Footer Button */
    .vh-image-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 3;
    }
    .vh-footer-buttons {
        display: flex;
    }
    .vh-btn-select, .vh-btn-buy-now {
        flex: 1;
        border: none;
        padding: 10px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .vh-btn-select { background: #47bd71; color: #fff; }
    .vh-btn-select:hover { background: #3aa862; }
    .vh-btn-buy-now { background: #fff; color: #333; border-top: 1px solid #eee; }
    .vh-btn-buy-now:hover { background: #f8f8f8; }

    /* Info Section */
    .vh-product-info {
        padding: 15px;
        text-align: center;
    }
    .vh-product-name {
        font-size: 15px;
        margin: 0 0 5px;
        font-weight: 500;
    }
    .vh-product-name a {
        color: #333;
        text-decoration: none;
    }
    .vh-product-category {
        font-size: 12px;
        color: #999;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    .vh-product-rating {
        margin-bottom: 10px;
        display: flex;
        justify-content: center;
    }
    .vh-product-price {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    .vh-price-new, .vh-price-current {
        font-weight: 700;
        color: #47bd71;
        font-size: 16px;
    }
    .vh-price-old {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
    }
</style>

<script>
    if (typeof toggleVariantOverlay !== 'function') {
        window.toggleVariantOverlay = function(productId) {
            const overlay = document.getElementById('variant-overlay-' + productId);
            if (overlay.style.display === 'flex') {
                overlay.style.display = 'none';
            } else {
                overlay.style.display = 'flex';
            }
        };

        window.buyNow = function(productId, isDirect = false) {
            let form;
            if (isDirect) {
                form = $('#vh-product-' + productId).find('.vh-image-footer form');
            } else {
                form = $('#variant-overlay-' + productId).find('form');
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }
            }

            // Add redirect param
            if (form.find('input[name="redirect"]').length === 0) {
                form.append('<input type="hidden" name="redirect" value="{{ route("checkout") }}">');
            }

            form.submit();
        };
    }
</script>

