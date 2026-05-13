<x-frontend-layout title="{{ $product->name }}" :breadcrumbs="$breadcrumbs" :seotags="$seotags">


    <!-- site__body -->
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb">
            <div class="container">
                <div class="block-header__body">
                    <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb__list">
                            @foreach($breadcrumb_list as $breadcrumb)
                                @if($loop->last)
                                    <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">
                                        <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                    </li>
                                @else
                                    <li class="breadcrumb__item breadcrumb__item--parent {{ $loop->first ? 'breadcrumb__item--first' : '' }}">
                                        <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="block-split">
            <div class="container">
                <div class="block-split__row row no-gutters">
                    <div class="block-split__item block-split__item-content col-auto">
                        <div class="product product--layout--full">
                            <div class="product__body">
                                <div class="product__card product__card--one"></div>
                                <div class="product__card product__card--two"></div>
                                <div class="product-gallery product-gallery--layout--product-full product__gallery" data-layout="product-full">
                                    <div class="product-gallery__featured">
                                        <button type="button" class="product-gallery__zoom">
                                            <svg width="24" height="24">
                                                <path d="M15,18c-2,0-3.8-0.6-5.2-1.7c-1,1.3-2.1,2.8-3.5,4.6c-2.2,2.8-3.4,1.9-3.4,1.9s-0.6-0.3-1.1-0.7c-0.4-0.4-0.7-1-0.7-1s-0.9-1.2,1.9-3.3c1.8-1.4,3.3-2.5,4.6-3.5C6.6,12.8,6,11,6,9c0-5,4-9,9-9s9,4,9,9S20,18,15,18z M15,2c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S18.9,2,15,2z M16,13h-2v-3h-3V8h3V5h2v3h3v2h-3V13z" />
                                            </svg>
                                        </button>
                                        <div class="owl-carousel" id="product-image">
                                            @if($product->media && $product->media->count() > 0)
                                                @foreach($product->media as $media)
                                                <a class="image image--type--product" href="{{ asset($media->path) }}" target="_blank">
                                                    <div class="image__body">
                                                        <img class="image__tag" src="{{ asset($media->path) }}" alt="{{ $product->name }}">
                                                    </div>
                                                </a>
                                                @endforeach
                                            @else
                                                <a class="image image--type--product" href="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}" target="_blank">
                                                    <div class="image__body">
                                                        <img class="image__tag" src="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}">
                                                    </div>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-gallery__thumbnails">
                                        <div class="owl-carousel" id="product-carousel">
                                            @if($product->media && $product->media->count() > 0)
                                                @foreach($product->media as $media)
                                                <div class="product-gallery__thumbnails-item image image--type--product">
                                                    <div class="image__body">
                                                        <img class="image__tag" src="{{ asset($media->path) }}" alt="{{ $product->name }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="product-gallery__thumbnails-item image image--type--product">
                                                    <div class="image__body">
                                                        <img class="image__tag" src="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="product__header">
                                    <h1 class="product__title">{{ $product->name }}</h1>
                                    <div class="product__subtitle">
                                        <div class="product__rating">
                                            <div class="product__rating-stars">
                                                <div class="rating">
                                                    <div class="rating__body">
                                                        <div class="rating__best">
                                                            <div class="rating__current" data-id="{{ $product->id }}" style="display: block; width: {{ ($product->reviews_avg_rating ?? 0) * 20 }}%;"></div>
                                                        </div>
                                                    </div>                                      
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product__main">
                                    <div class="product__excerpt">
                                        {{ Str::limit(strip_tags($product->description), 200) }}
                                    </div>
                                    @if(is_array($product->specification) && count($product->specification) > 0)
                                    <div class="product__features">
                                        <div class="product__features-title">Key Features:</div>
                                        <ul>
                                            @foreach(array_slice($product->specification, 0, 5) as $key => $value)
                                                <li>{{ ucfirst($key) }}: <span>{{ $value }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                <div class="product__info">
                                    <div class="product__info-card">
                                        <div class="product__info-body">
                                            @if($product->sale_price < $product->regular_price)
                                                <div class="tag-badge tag-badge--sale">Sale</div>                    
                                            @endif
                                            
                                            <div class="product__prices-stock">
                                                <div class="product__prices">
                                                    @if($product->sale_price < $product->regular_price)
                                                        <div class="product__price product__price--new">TK {{ number_format($product->sale_price, 2) }}</div>
                                                        <div class="product__price product__price--old">TK {{ number_format($product->regular_price, 2) }}</div>
                                                    @else
                                                        <div class="product__price product__price--current">TK {{ number_format($product->regular_price, 2) }}</div>
                                                    @endif
                                                </div>
                                                <div class="status-badge status-badge--style--{{ $product->total_stock > 0 ? 'success' : 'danger' }} product__stock status-badge--has-text">
                                                    <div class="status-badge__body">
                                                        <div class="status-badge__text">{{ $product->total_stock > 0 ? 'In Stock' : 'Out of Stock' }}</div>
                                                        <div class="status-badge__tooltip" tabindex="0" data-toggle="tooltip" title="{{ $product->total_stock > 0 ? 'In Stock' : 'Out of Stock' }}"></div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="product__meta">
                                                    <table>
                                                        <tr>
                                                            <th>SKU</th>
                                                            <td>{{ $product->sku ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Brand</th>
                                                            <td>{{ $product->brand ? $product->brand->name : 'N/A' }}</td>
                                                        </tr>
                                                        @if($product->manufacturer)
                                                        <tr>
                                                            <th>Manufacturer</th>
                                                            <td>{{ $product->manufacturer }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Category</th>
                                                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                        </div>
                                        <form class="product__options ms2_form" id="product-variant-form" onsubmit="return false;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}"/>
                                            <div class="product-form product__form">
                                                <div class="product-form__body">
                                                    @if($product->has_variant && $product->variants && $product->variants->count() > 0)
                                                        @php
                                                            // Group attribute items by attribute
                                                            $attributes = [];
                                                            foreach ($product->variants as $variant) {
                                                                foreach ($variant->variantItems as $item) {
                                                                    if ($item->attribute && $item->attributeItem) {
                                                                        $attributes[$item->attribute->name][$item->attributeItem->id] = $item->attributeItem->name;
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        @foreach($attributes as $attrName => $items)
                                                            <div class="product-form__item">
                                                                <span class="product-form__title">{{ $attrName }}</span>
                                                                <div class="product-form__control">
                                                                    <div class="form-group mb-0">
                                                                        @foreach($items as $itemId => $itemName)
                                                                            <div class="form-check form-check-inline">
                                                                                <input type="radio" class="form-check-input variant-option" name="attributes[{{ $attrName }}]" id="attr-{{ $itemId }}" value="{{ $itemId }}" {{ $loop->first ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="attr-{{ $itemId }}">{{ $itemName }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product__actions">
                                                <div class="product__actions-item product__actions-item--quantity">
                                                    <div class="input-number">
                                                        <input class="input-number__input form-control form-control-lg quantity" type="number" name="count" min="1" value="1" id="product-quantity">
                                                        <div class="input-number__add"></div>
                                                        <div class="input-number__sub"></div>
                                                    </div>
                                                </div>
                                                <div class="product__actions-item product__actions-item--addtocart">
                                                    <button type="button" class="btn btn-primary btn-lg btn-block btn-cart" 
                                                        id="product-add-to-cart"
                                                        data-id="{{ $product->id }}" 
                                                        data-name="{{ $product->name }}" 
                                                        data-price="{{ $product->sale_price }}" 
                                                        data-image="{{ $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg') }}" 
                                                        data-url="{{ route('product.show', $product->slug) }}">Add to cart</button>
                                                </div>
                                                <div class="product__actions-divider"></div>
                                                <button class="product__actions-item product__actions-item--wishlist msWishlistTogglePage" type="button">
                                                    <svg width="16" height="16">
                                                        <path d="M13.9,8.4l-5.4,5.4c-0.3,0.3-0.7,0.3-1,0L2.1,8.4c-1.5-1.5-1.5-3.8,0-5.3C2.8,2.4,3.8,2,4.8,2s1.9,0.4,2.6,1.1L8,3.7l0.6-0.6C9.3,2.4,10.3,2,11.3,2c1,0,1.9,0.4,2.6,1.1C15.4,4.6,15.4,6.9,13.9,8.4z" />
                                                    </svg>
                                                    <span>Add to Wishlist</span>
                                                </button>
                                                <button class="product__actions-item product__actions-item--compare msCompareTogglePage" type="button">
                                                    <svg width="16" height="16">
                                                        <path d="M9,15H7c-0.6,0-1-0.4-1-1V2c0-0.6,0.4-1,1-1h2c0.6,0,1,0.4,1,1v12C10,14.6,9.6,15,9,15z" />
                                                        <path d="M1,9h2c0.6,0,1,0.4,1,1v4c0,0.6-0.4,1-1,1H1c-0.6,0-1-0.4-1-1v-4C0,9.4,0.4,9,1,9z" />
                                                        <path d="M15,5h-2c-0.6,0-1,0.4-1,1v8c0,0.6,0.4,1,1,1h2c0.6,0,1-0.4,1-1V6C16,5.4,15.6,5,15,5z" />
                                                    </svg>
                                                    <span>Add to Compare</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="product__tabs product-tabs product-tabs--layout--full">
                                    <ul class="product-tabs__list">
                                        <li class="product-tabs__item product-tabs__item--active"><a href="#product-tab-description">Description</a></li>
                                        <li class="product-tabs__item"><a href="#product-tab-specification">Specification</a></li>
                                        <li class="product-tabs__item">
                                            <a href="#product-tab-reviews">
                                                Reviews
                                                <span class="product-tabs__item-counter">{{ $product->reviews_count ?? 0 }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="product-tabs__content">
                                        <div class="product-tabs__pane product-tabs__pane--active" id="product-tab-description">
                                            <div class="typography">
                                                {!! $product->description !!}
                                            </div>
                                        </div>
                                        <div class="product-tabs__pane" id="product-tab-specification">
                                            <div class="spec">
                                                <div class="spec__section">
                                                    <h4 class="spec__section-title">General</h4>
                                                    @if(is_array($product->specification) && count($product->specification) > 0)
                                                        @foreach($product->specification as $key => $value)
                                                            @if(!empty($value))
                                                            <div class="spec__row">
                                                                <div class="spec__name">{{ ucfirst($key) }}</div>
                                                                <div class="spec__value">{{ $value }}</div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="spec__row">
                                                            <div class="spec__value">No specifications available.</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-tabs__pane" id="product-tab-reviews">
                                            <div class="reviews-view">
                                                <div class="reviews-view__list">
                                                    <div class="reviews-list">
                                                        <ol class="reviews-list__content">
                                                            @if($product->reviews && count($product->reviews) > 0)
                                                                @foreach($product->reviews as $review)
                                                                    <li class="reviews-list__item">
                                                                        <div class="review">
                                                                            <div class="review__body">
                                                                                <div class="review__meta">
                                                                                    <div class="review__author">{{ $review->name ?? 'User' }}</div>
                                                                                    <div class="review__date">{{ $review->created_at ? $review->created_at->format('M d, Y') : '' }}</div>
                                                                                </div>
                                                                                <div class="review__content typography">
                                                                                    {{ $review->comment }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @else
                                                                <li>No reviews yet. Be the first to review!</li>
                                                            @endif
                                                        </ol>
                                                    </div>
                                                </div>
                                                <form class="reviews-view__form" id="comment-form" method="post" action="{{ route('review.store', $product->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}"/>
                                                    <h3 class="reviews-view__header">Write A Review</h3>
                                                    @if(session('success'))
                                                        <div class="alert alert-success">{{ session('success') }}</div>
                                                    @endif
                                                    @if($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul class="mb-0">
                                                                @foreach($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-xxl-8 col-xl-10 col-lg-9 col-12">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="review-stars">Review Stars</label>
                                                                    <div class="rating rating_active">
                                                                        <div class="rating__best">
                                                                            <div class="rating__current" data-id="{{ $product->id }}" style="display: block; width: 100%;"></div>
                                                                            <div class="rating__star rating__star_5" data-title="5"></div>
                                                                            <div class="rating__star rating__star_4" data-title="4"></div>
                                                                            <div class="rating__star rating__star_3" data-title="3"></div>
                                                                            <div class="rating__star rating__star_2" data-title="2"></div>
                                                                            <div class="rating__star rating__star_1" data-title="1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="rating" id="rating-input" value="5">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="review-author">Your Name</label>
                                                                    <input type="text" class="form-control" id="review-author" placeholder="Your Name" name="name" value="" required>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="review-email">Email Address</label>
                                                                    <input type="email" class="form-control" id="review-email" placeholder="Email Address" name="email" value="" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="review-text">Your Review</label>
                                                                <textarea class="form-control" id="review-text" rows="6" name="text" required></textarea>
                                                            </div>
                                                            <div class="form-group mb-0 mt-4">
                                                                <button type="submit" class="btn btn-primary">Post Your Review</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-space block-space--layout--divider-nl"></div>
                        <div class="block block-products-carousel" data-layout="grid-5">
                            <div class="container">
                                <div class="section-header">
                                    <div class="section-header__body">
                                        <h2 class="section-header__title">Related Products</h2>
                                        <div class="section-header__spring"></div>
                                        <div class="section-header__arrows">
                                            <div class="arrow section-header__arrow section-header__arrow--prev arrow--prev">
                                                <button class="arrow__button" type="button"><svg width="7" height="11">
                                                        <path d="M6.7,0.3L6.7,0.3c-0.4-0.4-0.9-0.4-1.3,0L0,5.5l5.4,5.2c0.4,0.4,0.9,0.3,1.3,0l0,0c0.4-0.4,0.4-1,0-1.3l-4-3.9l4-3.9C7.1,1.2,7.1,0.6,6.7,0.3z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="arrow section-header__arrow section-header__arrow--next arrow--next">
                                                <button class="arrow__button" type="button"><svg width="7" height="11">
                                                        <path d="M0.3,10.7L0.3,10.7c0.4,0.4,0.9,0.4,1.3,0L7,5.5L1.6,0.3C1.2-0.1,0.7,0,0.3,0.3l0,0c-0.4,0.4-0.4,1,0,1.3l4,3.9l-4,3.9
C-0.1,9.8-0.1,10.4,0.3,10.7z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="section-header__divider"></div>
                                    </div>
                                </div>
                                <div class="block-products-carousel__carousel">
                                    <div class="block-products-carousel__carousel-loader"></div>
                                    <div class="owl-carousel">
                                        @foreach($related_products as $item)
                                            @include('frontend.partials.product-card', ['product' => $item])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
    <!-- site__body / end -->


    @push('js')
        <script>
            $(document).ready(function() {
                // Handling review stars click
                $('.rating__star').click(function() {
                    var rating = $(this).data('title');
                    $('#rating-input').val(rating);
                    $('.rating__current').css('width', (rating * 20) + '%');
                });

                // Variants handling
                @if($product->has_variant && $product->variants)
                const variants = {!! json_encode($product->variants->map(function($v) {
                    return [
                        'id' => $v->id,
                        'sku' => $v->variant_sku,
                        'price' => $v->final_price ?? $v->variant_price,
                        'stock' => $v->variant_stock,
                        'attributes' => $v->attributeItems->pluck('id')->toArray()
                    ];
                })) !!};

                function updateVariantDetails() {
                    const selectedAttributes = [];
                    $('.variant-option:checked').each(function() {
                        selectedAttributes.push(parseInt($(this).val()));
                    });

                    // Find matching variant
                    const matchedVariant = variants.find(v => {
                        return selectedAttributes.every(attrId => v.attributes.includes(attrId)) && 
                               v.attributes.length === selectedAttributes.length;
                    });

                    if (matchedVariant) {
                        $('.product__price--current, .product__price--new').text('TK ' + parseFloat(matchedVariant.price).toFixed(2));
                        $('.product__meta table tr:first-child td').text(matchedVariant.sku);
                        
                        // Update stock badge
                        const stockText = matchedVariant.stock > 0 ? 'In Stock' : 'Out of Stock';
                        const stockClass = matchedVariant.stock > 0 ? 'success' : 'danger';
                        const badge = $('.status-badge');
                        badge.removeClass('status-badge--style--success status-badge--style--danger').addClass('status-badge--style--' + stockClass);
                        badge.find('.status-badge__text').text(stockText);

                        // Update Add to Cart button
                        $('#product-add-to-cart').data('price', matchedVariant.price);
                        $('#product-add-to-cart').data('id', matchedVariant.sku); // Use sku or variant ID
                    }
                }

                $('.variant-option').change(updateVariantDetails);
                updateVariantDetails(); // Initial call
                @endif
                
                // Add to cart click is handled by master.blade.php globally, but we must ensure it reads #product-quantity
                $(document).on('click', '#product-add-to-cart', function(e) {
                    // Update global quantity field used by .btn-cart handler
                    $('.quantity').val($('#product-quantity').val());
                });
            });
        </script>
    @endpush
</x-frontend-layout>
