<!-- mobile-menu -->
<!-- quickview-modal -->
<div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="quickview modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="quickview__close" data-dismiss="modal" aria-label="Close">
                <svg width="12" height="12">
                    <path d="M10.8,10.8L10.8,10.8c-0.4,0.4-1,0.4-1.4,0L6,7.4l-3.4,3.4c-0.4,0.4-1,0.4-1.4,0l0,0c-0.4-0.4-0.4-1,0-1.4L4.6,6L1.2,2.6
	c-0.4-0.4-0.4-1,0-1.4l0,0c0.4-0.4,1-0.4,1.4,0L6,4.6l3.4-3.4c0.4-0.4,1-0.4,1.4,0l0,0c0.4,0.4,0.4,1,0,1.4L7.4,6l3.4,3.4
	C11.2,9.8,11.2,10.4,10.8,10.8z"></path>
                </svg>
            </button>
            <div class="quickview__body-wrapper">
                <div class="quickview__body">
                    <div class="quickview-loading-spinner" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 320px; gap: 18px; width: 100%; color: #6b7280;">
                        <div style="width: 44px; height: 44px; border: 3.5px solid #f3f4f6; border-top: 3.5px solid var(--primary-green); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                        <span style="font-family: 'Outfit', sans-serif; font-size: 14.5px; font-weight: 550; letter-spacing: 0.3px;">Fetching product info...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .quickview-loading-spinner {
        display: flex !important;
    }
</style>
@endpush

@push('js')
<script>
    // AJAX Dynamic Quick View Scraper (Global)
    function ajaxOpenQuickView(productUrl) {
        const modal = $('#quickview-modal');
        if (!modal.length) return;
        
        // Show loading spinner inside standard theme structure
        modal.find('.quickview__body-wrapper').html(`
            <div class="quickview__body">
                <div class="quickview-loading-spinner" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 320px; gap: 18px; width: 100%; color: #6b7280;">
                    <div style="width: 44px; height: 44px; border: 3.5px solid #f3f4f6; border-top: 3.5px solid var(--primary-green); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                    <span style="font-family: 'Outfit', sans-serif; font-size: 14.5px; font-weight: 550; letter-spacing: 0.3px;">Fetching product info...</span>
                </div>
            </div>
        `);
        modal.modal('show');
        
        $.get(productUrl)
        .done(function(html) {
            const doc = $(html);
            const title = doc.find('.product__title').text().trim();
            const ratingHtml = doc.find('.product__rating').html() || '';
            const description = doc.find('.product__excerpt').html() || doc.find('.product__description').html() || '';
            const pricesHtml = doc.find('.product__prices').html();
            const stockHtml = doc.find('.product__prices-stock .status-badge').prop('outerHTML') || '';
            const metaHtml = doc.find('.product__meta table tbody').html() || '';
            const formBodyHtml = doc.find('.product-form__body').html();
            const productId = doc.find('input[name="id"]').val() || '';
            const seeDetailsUrl = productUrl;
            
            // Gather gallery images
            let galleryFeaturedHtml = '';
            let galleryThumbnailsHtml = '';
            
            const galleryFeaturedItems = doc.find('.product-gallery__featured .owl-item a, .product-gallery__featured a');
            if (galleryFeaturedItems.length > 0) {
                galleryFeaturedItems.each(function() {
                    const href = $(this).attr('href');
                    const img = $(this).find('img');
                    const src = img.attr('src') || href;
                    
                    galleryFeaturedHtml += `
                        <a class="image image--type--product" href="${href}" target="_blank">
                            <div class="image__body">
                                <img class="image__tag" src="${src}" alt="${title}">
                            </div>
                        </a>
                    `;
                });
                
                const galleryThumbItems = doc.find('.product-gallery__thumbnails .owl-item .product-gallery__thumbnails-item, .product-gallery__thumbnails .product-gallery__thumbnails-item');
                galleryThumbItems.each(function() {
                    const img = $(this).find('img');
                    const src = img.attr('src');
                    const isActive = $(this).hasClass('product-gallery__thumbnails-item--active');
                    
                    galleryThumbnailsHtml += `
                        <div class="product-gallery__thumbnails-item image image--type--product ${isActive ? 'product-gallery__thumbnails-item--active' : ''}">
                            <div class="image__body">
                                <img class="image__tag" src="${src}" alt="${title}">
                            </div>
                        </div>
                    `;
                });
            } else {
                const mainImg = doc.find('.product-image-link img, .primary-img').first().attr('src') || '{{ asset("images/no-image.jpg") }}';
                galleryFeaturedHtml = `
                    <a class="image image--type--product" href="${mainImg}" target="_blank">
                        <div class="image__body">
                            <img class="image__tag" src="${mainImg}" alt="${title}">
                        </div>
                    </a>
                `;
                
                galleryThumbnailsHtml = `
                    <div class="product-gallery__thumbnails-item image image--type--product product-gallery__thumbnails-item--active">
                        <div class="image__body">
                            <img class="image__tag" src="${mainImg}" alt="${title}">
                        </div>
                    </div>
                `;
            }
            
            // Construct dynamic split layout identical to theme markup
            const newHtml = `
                <div class="quickview__body">
                    <!-- Left Gallery -->
                    <div class="product-gallery product-gallery--layout--quickview quickview__gallery" data-layout="quickview">
                        <div class="product-gallery__featured">
                            <button type="button" class="product-gallery__zoom">
                                <svg width="24" height="24">
                                    <path d="M15,18c-2,0-3.8-0.6-5.2-1.7c-1,1.3-2.1,2.8-3.5,4.6c-2.2,2.8-3.4,1.9-3.4,1.9s-0.6-0.3-1.1-0.7c-0.4-0.4-0.7-1-0.7-1s-0.9-1.2,1.9-3.3c1.8-1.4,3.3-2.5,4.6-3.5C6.6,12.8,6,11,6,9c0-5,4-9,9-9s9,4,9,9S20,18,15,18z M15,2c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S18.9,2,15,2z M16,13h-2v-3h-3V8h3V5h2v3h3v2h-3V13z" />
                                </svg>
                            </button>
                            <div class="owl-carousel">
                                ${galleryFeaturedHtml}
                            </div>
                        </div>
                        <div class="product-gallery__thumbnails">
                            <div class="owl-carousel">
                                ${galleryThumbnailsHtml}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Product Info -->
                    <div class="quickview__product">
                        <div class="quickview__product-name">${title}</div>
                        <div class="quickview__product-rating" style="${ratingHtml ? '' : 'display: none;'}">
                            <div class="quickview__product-rating-stars">
                                <div class="rating">
                                    <div class="rating__body">
                                        ${ratingHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="quickview__product-meta">
                            <table>
                                <tbody>
                                    ${metaHtml}
                                </tbody>
                            </table>
                        </div>
                        <div class="quickview__product-description">
                            ${description}
                        </div>
                        <div class="quickview__product-prices-stock">
                            <div class="quickview__product-prices">
                                ${pricesHtml}
                            </div>
                            ${stockHtml}
                        </div>
                        
                        <!-- Form for variants -->
                        <form class="product-form quickview__product-form ms2_form" action="{{ route('cart.add') }}" method="POST">
                            <input type="hidden" name="id" value="${productId}"/>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="product-form__body">
                                ${formBodyHtml || ''}
                            </div>
                        </form>
                        
                        <!-- Dynamic Actions -->
                        <div class="quickview__product-actions">
                            <div class="quickview__product-actions-item quickview__product-actions-item--quantity">
                                <div class="input-number">
                                    <input class="input-number__input form-control quantity" type="number" name="count" min="1" value="1">
                                    <div class="input-number__add"></div>
                                    <div class="input-number__sub"></div>
                                </div>
                            </div>
                            <div class="quickview__product-actions-item quickview__product-actions-item--addtocart">
                                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="${seeDetailsUrl}" class="quickview__see-details">See full details</a>
            `;
            
            // Inject rebuilt layout structure
            modal.find('.quickview__body-wrapper').html(newHtml);
            
            // Re-bind quantity click buttons in injected container (using built-in helper if available)
            if ($.fn.customNumber) {
                modal.find('.input-number').customNumber();
            } else {
                modal.find('.input-number').each(function() {
                    const container = $(this);
                    const input = container.find('input');
                    const add = container.find('.input-number__add');
                    const sub = container.find('.input-number__sub');
                    
                    add.off('click').on('click', function() {
                        input.val(parseInt(input.val() || 1) + 1).trigger('change');
                    });
                    sub.off('click').on('click', function() {
                        const val = parseInt(input.val() || 1) - 1;
                        if (val >= 1) input.val(val).trigger('change');
                    });
                });
            }
            
            // Trigger the native shown.bs.modal event so the main.js closure script initializes the gallery and inputs perfectly!
            modal.trigger('shown.bs.modal');
        })
        .fail(function() {
            modal.find('.quickview__body-wrapper').html(`
                <div class="quickview__body">
                    <div style="text-align: center; color: #ef4444; font-weight: 600; padding: 40px 0; width: 100%;">Unable to open Quick View. Please try again.</div>
                </div>
            `);
        });
    }
</script>
@endpush
<!-- quickview-modal / end -->