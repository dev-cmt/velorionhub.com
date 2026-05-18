@php
    // Determine sale state
    $isSale = floatval($product->sale_price) < floatval($product->regular_price);
    $discountPercentage = 0;
    if ($isSale && floatval($product->regular_price) > 0) {
        $discountPercentage = round(((floatval($product->regular_price) - floatval($product->sale_price)) / floatval($product->regular_price)) * 100);
    }
    
    // Get categories and brand list
    $categoriesList = [];
    if ($product->category) {
        $categoriesList[] = '<a href="' . route('shop', ['category' => $product->category->slug]) . '">' . $product->category->name . '</a>';
    }
    if ($product->brand) {
        $categoriesList[] = '<a href="' . route('shop', ['brand' => $product->brand->slug]) . '">' . $product->brand->name . '</a>';
    }
    $categoriesHtml = count($categoriesList) > 0 ? implode(', ', $categoriesList) : '<a href="#">Jersey</a>';
    
    // Fallback images
    $mainImage = $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg');
    $hoverImage = $product->hover_image ? asset($product->hover_image) : null;

    // Get sizes/attributes if present
    $variantAttributes = [];
    if ($product->has_variant && $product->variants && $product->variants->count() > 0) {
        foreach ($product->variants as $variant) {
            foreach ($variant->variantItems as $item) {
                if ($item->attribute && $item->attributeItem) {
                    $variantAttributes[$item->attribute->name][$item->attributeItem->id] = $item->attributeItem->name;
                }
            }
        }
    }
    
    // Determine Wishlist state
    $inWishlist = false;
    try {
        $wishlistCart = \Cart::session((Auth::id() ?? session()->getId()) . '_wishlist');
        if ($wishlistCart && $wishlistCart->get($product->id)) {
            $inWishlist = true;
        }
    } catch (\Exception $e) {}

    // Determine Compare state
    $inCompare = false;
    try {
        $compareCart = \Cart::session((Auth::id() ?? session()->getId()) . '_compare');
        if ($compareCart && $compareCart->get($product->id)) {
            $inCompare = true;
        }
    } catch (\Exception $e) {}
@endphp

<div class="wd-product-card text-center" 
     data-id="{{ $product->id }}"
     data-variants='{!! json_encode($product->variants->map(function($v) {
         return [
             'id' => $v->id,
             'sku' => $v->variant_sku,
             'price' => $v->final_price ?? $v->variant_price,
             'stock' => $v->variant_stock,
             'attributes' => $v->variantItems->pluck('attribute_item_id')->toArray()
         ];
     })) !!}'>
    
    <div class="product-image-link">
        <!-- Discount badge -->
        @if($isSale)
            <span class="badge-discount">-{{ $discountPercentage }}%</span>
        @endif
        @if($product->total_stock <= 0)
            <span class="badge-discount" style="background-color: #374151; left: auto; right: 15px;">Out of Stock</span>
        @endif

        <!-- Image Link -->
        <a href="{{ route('product.show', $product->slug) }}">
            <img src="{{ $mainImage }}" class="primary-img" alt="{{ $product->name }}" loading="lazy">
            @if($hoverImage)
                <img src="{{ $hoverImage }}" class="hover-img" alt="{{ $product->name }}" loading="lazy">
            @endif
        </a>

        <!-- Sizing attributes overlay covering the top of the image container -->
        @if(count($variantAttributes) > 0)
            <div class="wd-quick-shop-overlay" style="display: none;">
                <button type="button" class="wd-quick-shop-close-btn">
                    <i class="bi bi-x"></i> Close
                </button>
                <div class="wd-quick-shop-content">
                    @foreach($variantAttributes as $attrName => $items)
                        <div class="wd-attribute-group" data-attribute-name="{{ $attrName }}">
                            <div class="wd-attribute-title">{{ $attrName }}:</div>
                            <div class="wd-attribute-options">
                                @foreach($items as $itemId => $itemName)
                                    <button type="button" class="wd-attribute-option-box" data-value="{{ $itemId }}">
                                        {{ $itemName }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Side action buttons stack (Compare, Quick View, Wishlist) -->
        <div class="wd-buttons">
            <!-- Compare -->
            <button type="button" class="wd-action-btn wd-compare-btn {{ $inCompare ? 'wd-action-btn--active' : '' }}" 
                    title="Compare" 
                    onclick="ajaxToggleCompare(this, {{ $product->id }})">
                <i class="bi bi-arrow-left-right"></i>
            </button>
            
            <!-- Quick View -->
            <button type="button" class="wd-action-btn" 
                    title="Quick View" 
                    onclick="ajaxOpenQuickView('{{ route('product.show', $product->slug) }}')">
                <i class="bi bi-search"></i>
            </button>
            
            <!-- Wishlist -->
            <button type="button" class="wd-action-btn wd-wishlist-btn {{ $inWishlist ? 'wd-action-btn--active' : '' }}" 
                    title="{{ $inWishlist ? 'Browse Wishlist' : 'Add to Wishlist' }}" 
                    onclick="ajaxToggleWishlist(this, {{ $product->id }})">
                <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }}"></i>
            </button>
        </div>

        <!-- Green add to cart / select options slide-up action bar -->
        <div class="wd-add-btn">
            @if($product->total_stock > 0)
                @if(count($variantAttributes) > 0)
                    <button type="button" class="btn btn-select-options wd-btn-select">
                        Select Options
                    </button>
                @else
                    <button type="button" class="btn btn-select-options btn-cart"
                            data-id="{{ $product->id }}" 
                            data-name="{{ $product->name }}" 
                            data-price="{{ $product->sale_price }}" 
                            data-image="{{ $mainImage }}" 
                            data-url="{{ route('product.show', $product->slug) }}">
                        Add to Cart
                    </button>
                @endif
            @else
                <button type="button" class="btn btn-select-options btn-select-options--out" disabled>
                    Out of Stock
                </button>
            @endif
        </div>
    </div>

    <!-- Product Text Details (Title, Category, Price) -->
    <div class="wd-product-content">
        <h3 class="wd-entities-title">
            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="wd-product-cats">
            {!! $categoriesHtml !!}
        </div>
        <div class="price" data-original-val="TK {{ number_format($product->sale_price, 2) }}">
            @if($isSale)
                <span class="woodmart-price-new">TK {{ number_format($product->sale_price, 2) }}</span>
                <span class="woodmart-price-old">TK {{ number_format($product->regular_price, 2) }}</span>
            @else
                <span class="woodmart-price-current">TK {{ number_format($product->sale_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>

@once
    @push('css')
        <style>
            /* Loading Bootstrap Icons stylesheet dynamically */
            @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');

            :root {
                --primary-green: #439665; /* Theme green color */
            }

            /* --- Main Card Wrapper --- */
            .wd-product-card {
                background: #ffffff;
                transition: all 0.35s ease;
                width: 100%;
                margin: auto;
                position: relative;
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
            }

            /* --- Image Container Aspect Ratio & Overlay Setup --- */
            .product-image-link {
                position: relative;
                display: block;
                overflow: hidden;
                background-color: #f5f5f5;
                width: 100%;
                padding-top: 114.28%; /* Perfect 7:8 ratio */
            }

            .product-image-link a {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .primary-img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: opacity 0.5s ease, transform 0.5s ease;
            }

            .hover-img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity 0.5s ease, transform 0.5s ease;
                object-fit: cover;
            }

            /* Image transitions on hover */
            .product-image-link:hover .primary-img {
                transform: scale(1.02);
            }

            .product-image-link:hover .hover-img {
                opacity: 1;
                transform: scale(1.02);
            }

            /* --- Quick Shop Sizing Overlay --- */
            .wd-quick-shop-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.96);
                z-index: 10;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                box-sizing: border-box;
                padding: 20px 15px;
                transition: opacity 0.3s ease;
            }

            .wd-quick-shop-close-btn {
                position: absolute;
                top: 12px;
                right: 12px;
                background: transparent;
                border: none;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                color: #777777;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 5px;
                z-index: 12;
                outline: none !important;
            }

            .wd-quick-shop-close-btn:hover {
                color: #111827;
            }

            .wd-quick-shop-content {
                width: 100%;
                text-align: center;
            }

            .wd-attribute-group {
                margin-bottom: 10px;
            }

            .wd-attribute-title {
                font-family: 'Outfit', 'Segoe UI', sans-serif;
                font-size: 11.5px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .wd-attribute-options {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 7px;
            }

            .wd-attribute-option-box {
                background: #ffffff;
                border: 1px solid rgba(0, 0, 0, 0.15);
                color: #374151;
                padding: 5px 12px;
                font-size: 12px;
                font-weight: 700;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s ease;
                outline: none !important;
            }

            .wd-attribute-option-box:hover {
                border-color: #111827;
                color: #111827;
            }

            .wd-attribute-option-box.active {
                background: #111827;
                color: #ffffff;
                border-color: #111827;
            }

            /* --- Side Action Floating overlay (Top-Right) --- */
            .wd-buttons {
                position: absolute;
                top: 15px;
                right: 15px;
                display: flex;
                flex-direction: column;
                gap: 8px;
                z-index: 5;
                transform: translateX(50px);
                transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), 
                            opacity 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                opacity: 0;
            }

            .product-image-link:hover .wd-buttons {
                transform: translateX(0);
                opacity: 1;
            }

            .wd-action-btn {
                background: #ffffff;
                color: #333333;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
                text-decoration: none !important;
                transition: background 0.2s, color 0.2s, transform 0.2s;
                border: none;
                padding: 0;
                cursor: pointer;
                outline: none !important;
            }

            .wd-action-btn:hover {
                background: var(--primary-green);
                color: #ffffff;
                transform: scale(1.08);
            }

            .wd-action-btn--active {
                color: var(--primary-green);
            }

            /* --- Sliding green button overlay --- */
            .wd-add-btn {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                transform: translateY(100%);
                transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                z-index: 9;
            }

            .product-image-link:hover .wd-add-btn,
            .product-image-link.overlay-active .wd-add-btn {
                transform: translateY(0);
            }

            .btn-select-options {
                background: var(--primary-green);
                color: #ffffff;
                text-transform: uppercase;
                font-family: 'Outfit', 'Segoe UI', sans-serif;
                font-size: 12.5px;
                font-weight: 700;
                letter-spacing: 0.5px;
                border-radius: 0;
                padding: 12px;
                width: 100%;
                border: none;
                cursor: pointer;
                transition: background 0.25s, opacity 0.2s;
                outline: none !important;
            }

            .btn-select-options:hover {
                background: #357d54;
                color: #ffffff;
            }

            .btn-select-options--out {
                background: #6b7280;
                cursor: not-allowed;
            }

            .btn-select-options--out:hover {
                background: #6b7280;
            }

            /* --- Typography & Info Alignment --- */
            .wd-product-content {
                padding: 15px 10px;
                text-align: center;
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }

            .wd-entities-title {
                font-family: 'Outfit', 'Segoe UI', sans-serif;
                font-size: 15.5px;
                margin-top: 5px;
                margin-bottom: 5px;
                font-weight: 600;
                line-height: 1.35;
                height: 40px;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }

            .wd-entities-title a {
                color: #242424;
                text-decoration: none;
                transition: color 0.2s;
            }

            .wd-entities-title a:hover {
                color: var(--primary-green);
            }

            .wd-product-cats {
                font-family: 'Roboto', 'Segoe UI', sans-serif;
                font-size: 12px;
                color: #bbbbbb;
                margin-bottom: 8px;
                font-weight: 500;
            }

            .wd-product-cats a {
                color: #bbbbbb;
                text-decoration: none;
                transition: color 0.2s;
            }

            .wd-product-cats a:hover {
                color: #888888;
            }

            .price {
                color: var(--primary-green);
                font-family: 'Outfit', 'Segoe UI', sans-serif;
                font-weight: 700;
                font-size: 16.5px;
                margin-top: auto;
            }

            .woodmart-price-new {
                color: var(--primary-green);
            }

            .woodmart-price-old {
                color: #bbbbbb;
                text-decoration: line-through;
                font-size: 13px;
                margin-left: 6px;
                font-weight: 500;
            }

            /* --- Badges --- */
            .badge-discount {
                position: absolute;
                top: 15px;
                left: 15px;
                background-color: var(--primary-green);
                color: #ffffff;
                padding: 4px 10px;
                font-family: 'Outfit', 'Segoe UI', sans-serif;
                font-size: 11px;
                font-weight: 700;
                border-radius: 50px;
                z-index: 6;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                pointer-events: none;
            }
        </style>
    @endpush

    @push('js')
        <script>
            $(document).ready(function() {
                // 1. Select Options button click triggers the overlay
                $(document).on('click', '.wd-btn-select', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const btn = $(this);
                    const card = btn.closest('.wd-product-card');
                    const imgLink = card.find('.product-image-link');
                    const overlay = card.find('.wd-quick-shop-overlay');
                    
                    // Show sizing overlay inside the image area
                    imgLink.addClass('overlay-active');
                    overlay.fadeIn(200);
                    
                    // Transform select options trigger to add to cart (initially disabled)
                    btn.text('Add to Cart')
                       .addClass('btn-add-cart-pending')
                       .removeClass('wd-btn-select')
                       .css('opacity', '0.6')
                       .prop('disabled', true);
                });

                // 2. Size / Attribute options click selection logic
                $(document).on('click', '.wd-attribute-option-box', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const optionBox = $(this);
                    const attrGroup = optionBox.closest('.wd-attribute-group');
                    const card = optionBox.closest('.wd-product-card');
                    const overlay = card.find('.wd-quick-shop-overlay');
                    const btn = card.find('.btn-select-options');
                    
                    // Highlight selected box
                    attrGroup.find('.wd-attribute-option-box').removeClass('active');
                    optionBox.addClass('active');
                    
                    // Collect selected options to match variant
                    const allSelected = [];
                    overlay.find('.wd-attribute-group').each(function() {
                        const group = $(this);
                        const activeOpt = group.find('.wd-attribute-option-box.active');
                        if (activeOpt.length) {
                            allSelected.push(parseInt(activeOpt.attr('data-value')));
                        }
                    });
                    
                    const totalGroups = overlay.find('.wd-attribute-group').length;
                    if (allSelected.length === totalGroups) {
                        const variants = JSON.parse(card.attr('data-variants') || '[]');
                        const matchedVariant = variants.find(v => {
                            return allSelected.every(attrId => v.attributes.includes(attrId)) &&
                                   v.attributes.length === allSelected.length;
                        });
                        
                        if (matchedVariant) {
                            // Update price text on match
                            card.find('.price').text('TK ' + parseFloat(matchedVariant.price).toFixed(2));
                            
                            // Enable action button payload parameters
                            btn.removeClass('btn-add-cart-pending')
                               .addClass('btn-cart')
                               .attr('data-id', matchedVariant.id)
                               .attr('data-price', matchedVariant.price)
                               .css('opacity', '1')
                               .prop('disabled', false);
                        }
                    }
                });

                // 3. Quick Shop overlay Close click
                $(document).on('click', '.wd-quick-shop-close-btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const closeBtn = $(this);
                    const card = closeBtn.closest('.wd-product-card');
                    const imgLink = card.find('.product-image-link');
                    const overlay = card.find('.wd-quick-shop-overlay');
                    const btn = card.find('.btn-select-options');
                    
                    // Hide overlay
                    overlay.fadeOut(200);
                    imgLink.removeClass('overlay-active');
                    
                    // Restore original select button styling
                    btn.text('Select Options')
                       .removeClass('btn-cart btn-add-cart-pending')
                       .addClass('wd-btn-select')
                       .css('opacity', '1')
                       .prop('disabled', false)
                       .removeAttr('data-id')
                       .removeAttr('data-price');
                       
                    // Clear option highlightings
                    overlay.find('.wd-attribute-option-box').removeClass('active');
                    
                    // Restore primary catalog price element
                    const originalPrice = card.find('.price').attr('data-original-val');
                    if (originalPrice) {
                        card.find('.price').text(originalPrice);
                    }
                });
            });

            // Unified AJAX Toggle Wishlist
            function ajaxToggleWishlist(button, productId) {
                const btn = $(button);
                btn.prop('disabled', true);
                
                $.post("{{ route('wishlist.add') }}", {
                    _token: "{{ csrf_token() }}",
                    id: productId
                })
                .done(function(response) {
                    if (response.success) {
                        btn.toggleClass('wd-action-btn--active');
                        const isAdded = btn.hasClass('wd-action-btn--active');
                        
                        if (isAdded) {
                            btn.attr('title', 'Remove from Wishlist');
                            btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                            showPremiumToast('Product added to wishlist!', 'success');
                        } else {
                            btn.attr('title', 'Add to Wishlist');
                            btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                            showPremiumToast('Product removed from wishlist.', 'success');
                        }
                        
                        if (response.count !== undefined) {
                            $('.wishlist-count strong, .wishlist-count').text(response.count);
                        }
                    } else {
                        showPremiumToast('Something went wrong. Please try again.', 'error');
                    }
                })
                .fail(function() {
                    showPremiumToast('Unable to complete request. Please log in.', 'error');
                })
                .always(function() {
                    btn.prop('disabled', false);
                });
            }

            // Unified AJAX Toggle Compare
            function ajaxToggleCompare(button, productId) {
                const btn = $(button);
                btn.prop('disabled', true);
                
                $.post("{{ route('compare.add') }}", {
                    _token: "{{ csrf_token() }}",
                    id: productId
                })
                .done(function(response) {
                    if (response.success) {
                        btn.toggleClass('wd-action-btn--active');
                        const isAdded = btn.hasClass('wd-action-btn--active');
                        
                        if (isAdded) {
                            showPremiumToast('Product added to compare list!', 'success');
                        } else {
                            showPremiumToast('Product removed from compare list.', 'success');
                        }
                        
                        if (response.count !== undefined) {
                            $('.compare-count, #msCompare').text(response.count);
                        }
                    } else {
                        showPremiumToast('Something went wrong. Please try again.', 'error');
                    }
                })
                .fail(function() {
                    showPremiumToast('Unable to complete request.', 'error');
                })
                .always(function() {
                    btn.prop('disabled', false);
                });
            }



            // Glassmorphic Notification Toast System
            function showPremiumToast(message, type = 'success') {
                let container = document.getElementById('premium-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'premium-toast-container';
                    container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 999999; display: flex; flex-direction: column; gap: 12px; max-width: 360px; width: calc(100% - 48px); pointer-events: none;';
                    document.body.appendChild(container);
                }
                
                const toast = document.createElement('div');
                toast.style.cssText = `
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(8px);
                    border-left: 4px solid ${type === 'success' ? 'var(--primary-green)' : '#ef4444'};
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                    border-radius: 8px;
                    padding: 15px 20px;
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    transform: translateY(-20px) scale(0.9);
                    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
                    pointer-events: auto;
                    opacity: 0;
                `;
                
                const icon = type === 'success' 
                    ? `<svg width="22" height="22" fill="none" stroke="var(--primary-green)" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
                    : `<svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
                    
                toast.innerHTML = `
                    <span style="flex-shrink: 0; display: flex; align-items: center;">${icon}</span>
                    <div style="flex-grow: 1; font-family: 'Outfit', 'Segoe UI', sans-serif; font-size: 13.5px; font-weight: 600; color: #1f2937; line-height: 1.4;">${message}</div>
                    <button style="background: none; border: none; padding: 0; cursor: pointer; color: #9ca3af; display: flex; outline: none;" onclick="this.parentElement.style.opacity='0'; setTimeout(()=>this.parentElement.remove(),300)">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                
                container.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.transform = 'translateY(0) scale(1)';
                    toast.style.opacity = '1';
                }, 20);
                
                setTimeout(() => {
                    toast.style.transform = 'translateY(-20px) scale(0.9)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 400);
                }, 3800);
            }
        </script>
    @endpush
@endonce