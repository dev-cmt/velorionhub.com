(function ($) {
    'use strict';

    const routes = window.VelorionRoutes || {};

    function csrf() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    function formatPrice(value) {
        const num = parseFloat(value) || 0;
        return 'TK ' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function cartAjax(url, data, method) {
        return $.ajax({
            url: url,
            method: method || 'POST',
            dataType: 'json',
            data: Object.assign({ _token: csrf() }, data || {}),
        });
    }

    function updateCartCount(count) {
        $('.cart-count, .nav-cart .count-box, a[href="#shoppingCart"] .count-box').text(count);
    }

    function updateWishlistCount(count) {
        count = parseInt(count, 10) || 0;
        // Update the badge counter
        $('a[href*="wishlist"] .count-box.wishlist-count').text(count);
        // Update the icon count-box (navbar)
        $('.count-box.wishlist-count').text(count);
        // Update the text label next to icon (e.g. "3 item")
        $('h6.wishlist-count').text(count + ' item');
    }

    function updateCompareCount(count) {
        count = parseInt(count, 10) || 0;
        $('.compare-count, #msCompare').text(count);
    }

    function renderMiniCart(res) {
        const cart = $('#shoppingCart');
        const list = cart.find('.popup-body.product-list-wrap');
        const empty = cart.find('.minicart-empty');
        const footer = cart.find('.popup-footer');

        list.empty();

        if (!res.count || !res.items || res.items.length === 0) {
            empty.show();
            list.hide();
            footer.hide();
            updateCartCount(0);
            $('a[href="#shoppingCart"] .number-item').text(formatPrice(0));
            return;
        }

        empty.hide();
        list.show();
        footer.show();
        updateCartCount(res.count);

        res.items.forEach(function (item) {
            const image = (item.attributes && item.attributes.image) ? item.attributes.image : (routes.noImage || '');
            const url = (item.attributes && item.attributes.url) ? item.attributes.url : '#';
            const variantLabel = (item.attributes && item.attributes.variant_label) ? item.attributes.variant_label : '';

            list.append(`
                <li class="file-delete" data-cart-id="${item.id}">
                    <div class="card-product style-row row-small-2 align-items-center">
                        <div class="card-product-wrapper">
                            <a href="${url}" class="product-img">
                                <img class="img-product lazyload" src="${image}" data-src="${image}" alt="${item.name}">
                            </a>
                        </div>
                        <div class="card-product-info">
                            <div class="box-title">
                                <a href="${url}" class="name-product body-md-2 fw-semibold text-secondary link">${item.name}</a>
                                ${variantLabel ? `<span class="variant-label text-muted d-block small">${variantLabel}</span>` : ''}
                                <p class="price-wrap fw-medium">
                                    <span class="new-price price-text fw-medium">${formatPrice(item.price)}</span>
                                </p>
                                <p class="body-md-2">X${item.quantity}</p>
                            </div>
                        </div>
                        <span class="icon-close remove link" data-cart-id="${item.id}"></span>
                    </div>
                </li>
            `);
        });

        cart.find('.price-amount.product-title').text(formatPrice(res.subtotal));
        $('a[href="#shoppingCart"] .number-item').text(formatPrice(res.subtotal));
    }

    function appendCompareItem(product) {
        const wrap = $('.offcanvas-compare .tf-compare-wrap');
        const empty = $('.offcanvas-compare .mini-compare-empty');

        // Already in compare drawer? skip
        if (wrap.find(`.tf-compare-item[data-id="${product.id}"]`).length) {
            return;
        }

        empty.hide();
        wrap.show();

        wrap.append(`
            <div class="tf-compare-item" data-id="${product.id}">
                <span class="icon-close remove" data-compare-id="${product.id}"></span>
                <a href="${product.url}" class="image">
                    <img class="lazyload" src="${product.image}" data-src="${product.image}" alt="${product.name}">
                </a>
                <div class="content">
                    <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link" href="${product.url}">${product.name}</a>
                    <p class="price-wrap fw-medium">
                        <span class="new-price price-text fw-medium">${formatPrice(product.price)}</span>
                    </p>
                </div>
            </div>
        `);
    }

    function removeCompareItem(productId) {
        $('.offcanvas-compare .tf-compare-wrap .tf-compare-item[data-id="' + productId + '"]').remove();
        if ($('.offcanvas-compare .tf-compare-item').length === 0) {
            $('.mini-compare-empty').show();
            $('.tf-compare-wrap').hide();
        }
    }

    function loadMiniCart() {
        if (!routes.cartMini) return;
        $.get(routes.cartMini).done(renderMiniCart);
    }

    function loadWishlistCount() {
        if (!routes.wishlistAdd) return;
        $.post(routes.wishlistAdd, { _token: csrf() }, null, 'json').done(function (res) {
            var count = (res && typeof res.count !== 'undefined') ? parseInt(res.count, 10) : 0;
            updateWishlistCount(count);
        });
    }

    function loadCompareCount() {
        if (!routes.compareAdd) return;
        $.post(routes.compareAdd, { _token: csrf() }, null, 'json').done(function (res) {
            var count = (res && typeof res.count !== 'undefined') ? parseInt(res.count, 10) : 0;
            updateCompareCount(count);
        });
    }

    function getProductDataFromCard($el) {
        const card = $el.closest('.card-product');
        const addBtn = card.find('.add-to-cart').first();
        return {
            id: addBtn.data('id') || $el.data('id'),
            name: addBtn.data('name') || '',
            price: addBtn.data('price') || 0,
            image: addBtn.data('image') || routes.noImage || '',
            url: addBtn.data('url') || '#',
        };
    }

    function updateQuickViewVariantDetails() {
        const modal = $('#quickView');
        const variants = modal.data('variants');
        if (!variants || !variants.length) return;

        const selectedAttributes = [];
        modal.find('.variant-option:checked').each(function () {
            selectedAttributes.push(parseInt($(this).val()));
        });

        // Find matched variant
        const matchedVariant = variants.find(function (v) {
            return selectedAttributes.every(function (attrId) {
                return v.attributes.indexOf(attrId) !== -1;
            }) && v.attributes.length === selectedAttributes.length;
        });

        const addBtn = modal.find('.btn-add-quickview');

        if (matchedVariant) {
            // Update SKU inside modal
            modal.find('#qv-display-sku').text(matchedVariant.sku);

            // Update Price inside modal
            const salePrice = parseFloat(matchedVariant.price);
            const regularPrice = parseFloat(matchedVariant.regular_price);

            modal.find('.product-info-price h4').text(formatPrice(salePrice));
            if (regularPrice > salePrice) {
                modal.find('.product-info-price .old-price').text(formatPrice(regularPrice)).show();
            } else {
                modal.find('.product-info-price .old-price').hide();
            }

            // Update Stock Status inside modal
            const stockStatusContainer = modal.find('#qv-display-stock-status');
            if (matchedVariant.stock > 0) {
                stockStatusContainer.html('<span class="badge bg-success">In Stock (' + matchedVariant.stock + ' available)</span>');
                addBtn.prop('disabled', false).css({ opacity: 1, 'cursor': 'pointer' }).html('<span class="text-white">Add To Cart</span>');
            } else {
                stockStatusContainer.html('<span class="badge bg-secondary">Out of Stock</span>');
                addBtn.prop('disabled', true).css({ opacity: 0.6, 'cursor': 'not-allowed' }).html('<span class="text-white">Out of Stock</span>');
            }

            // Update addBtn payload price
            addBtn.attr('data-price', matchedVariant.price)
                .data('price', matchedVariant.price);
        } else {
            // No matching variant?
            modal.find('#qv-display-stock-status').html('<span class="badge bg-secondary">Unavailable</span>');
            addBtn.prop('disabled', true).css({ opacity: 0.6, 'cursor': 'not-allowed' }).html('<span class="text-white">Unavailable</span>');
        }
    }

    function openQuickView(url) {
        const modal = $('#quickView');
        if (!modal.length || !url) return;

        modal.find('.product-info-name a').text('Loading...');
        modal.modal('show');

        $.get(url).done(function (html) {
            const doc = $(html);
            const title = doc.find('.product-info-name').first().text().trim()
                || doc.find('.product__title').first().text().trim()
                || 'Product';

            const price = doc.find('#display-product-price h4').first().text().trim()
                || doc.find('.product__price--new, .product__price--current').first().text().trim()
                || doc.find('.product__prices').first().text().trim();

            const oldPrice = doc.find('#display-product-price .old-price').first().text().trim()
                || doc.find('.product__price--old').first().text().trim();

            const description = doc.find('.tab-des .body-text-3').first().text().trim()
                || doc.find('.product__excerpt, .product__description').first().text().trim()
                || '';

            const productId = doc.find('.add-to-cart, .order-now').first().attr('data-id')
                || doc.find('input[name="id"]').val()
                || '';

            const images = [];
            doc.find('.tf-product-media-main img, .product-gallery__featured img, .product__gallery img, .image__tag').each(function () {
                const src = $(this).attr('src') || $(this).attr('data-src') || $(this).attr('data-zoom');
                if (src && images.indexOf(src) === -1) {
                    images.push(src);
                }
            });

            if (!images.length) {
                images.push(routes.noImage || '');
            }

            const mainSlides = images.map(function (src) {
                return `<div class="swiper-slide"><a href="${url}" class="d-block tf-image-view"><img src="${src}" data-src="${src}" alt="${title}" class="lazyload"></a></div>`;
            }).join('');

            const thumbSlides = images.map(function (src) {
                return `<div class="swiper-slide"><div class="item"><img src="${src}" alt="${title}"></div></div>`;
            }).join('');

            modal.find('.tf-product-view-main .swiper-wrapper').html(mainSlides);
            modal.find('.tf-product-view-thumbs .swiper-wrapper').html(thumbSlides);
            modal.find('.product-info-name a').attr('href', url).text(title || 'Product');
            modal.find('.product-info-price h4').text(price || '');
            modal.find('.product-info-price .old-price').text(oldPrice || '').toggle(!!oldPrice);

            if (description) {
                modal.find('.product-about-list').html(`<li><p class="body-text-3">${description.substring(0, 200)}</p></li>`);
            }

            // Rate wrap & SKU
            const rateWrap = doc.find('.product-info-rate-wrap').html();
            if (rateWrap) {
                modal.find('.product-info-rate-wrap').html(rateWrap);
                modal.find('#display-sku').attr('id', 'qv-display-sku');
            }

            // Brand & features
            const featureList = doc.find('.product-fearture-list').html();
            if (featureList) {
                modal.find('.product-fearture-list').html(featureList);
            }

            // Stock status
            modal.find('#qv-display-stock-status').remove();
            const stockEl = doc.find('#display-stock-status');
            if (stockEl.length) {
                modal.find('.product-info-price').after(stockEl.clone().attr('id', 'qv-display-stock-status'));
            }

            // Reset quantity input
            modal.find('.quantity-product').val(1);

            // Variants JSON handling
            const variantsJsonEl = doc.find('#product-variants-json');
            let variants = [];
            if (variantsJsonEl.length) {
                try {
                    variants = JSON.parse(variantsJsonEl.html());
                } catch (e) {
                    console.error("Failed to parse variants JSON", e);
                }
            }
            modal.data('variants', variants);

            // Clone and insert variants picker HTML
            const qvVariantsContainer = modal.find('.quickview-variants-container');
            const productFormItems = doc.find('.product-form__item');
            if (productFormItems.length) {
                const clonedItems = productFormItems.clone();
                clonedItems.find('input.variant-option').each(function () {
                    const id = $(this).attr('id');
                    if (id) {
                        $(this).attr('id', 'qv-' + id);
                    }
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', 'qv-' + name);
                    }
                });
                clonedItems.find('label.variant-swatch-label').each(function () {
                    const forAttr = $(this).attr('for');
                    if (forAttr) {
                        $(this).attr('for', 'qv-' + forAttr);
                    }
                });
                qvVariantsContainer.html(clonedItems).show();
                updateQuickViewVariantDetails();
            } else {
                qvVariantsContainer.empty().hide();
            }

            modal.find('.btn-add-quickview')
                .attr('data-id', productId)
                .attr('data-name', title)
                .attr('data-price', price.replace(/[^\d.]/g, ''))
                .attr('data-image', images[0])
                .attr('data-url', url);

            if (typeof Swiper !== 'undefined') {
                modal.find('.tf-product-view-main, .tf-product-view-thumbs').each(function () {
                    if (this.swiper) {
                        this.swiper.destroy(true, true);
                    }
                });

                const $slider = modal.find('.product-thumb-slider');
                const $thumbs = $slider.find('.tf-product-view-thumbs');
                const $main = $slider.find('.tf-product-view-main');
                const direction = $thumbs.data('direction') || 'horizontal';

                const thumbsSwiper = new Swiper($thumbs[0], {
                    direction: 'horizontal',
                    spaceBetween: 10,
                    slidesPerView: 'auto',
                    freeMode: true,
                    watchSlidesProgress: true,
                    observer: true,
                    observeParents: true,
                    nested: true,
                    breakpoints: {
                        0: {
                            direction: 'horizontal',
                        },
                        576: {
                            direction: direction,
                        },
                    },
                });

                const mainSwiper = new Swiper($main[0], {
                    spaceBetween: 10,
                    observer: true,
                    observeParents: true,
                    speed: 800,
                    thumbs: {
                        swiper: thumbsSwiper,
                    },
                    navigation: {
                        nextEl: $slider.find('.single-slide-next')[0],
                        prevEl: $slider.find('.single-slide-prev')[0],
                    },
                });

                $thumbs.find('.swiper-slide').on('mouseenter', function () {
                    let index = $(this).index();
                    mainSwiper.slideTo(index);
                });
            }
        }).fail(function () {
            window.location.href = url;
        });
    }

    window.ajaxOpenQuickView = function (url) {
        openQuickView(url);
    };

    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': csrf() },
        });

        loadMiniCart();
        loadWishlistCount();
        loadCompareCount();

        // ─── Add to Cart (product cards) ─────────────────────────────────────
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();
            const btn = $(this);

            // If product has variants and we're on a product card (not product detail page)
            // redirect to the product page so the user can select a variant first
            if (btn.data('has-variant') == '1' && !btn.closest('#quickView, .tf-product-info-list, form').length) {
                const productUrl = btn.data('product-url') || btn.data('url');
                if (productUrl) {
                    window.location.href = productUrl;
                    return;
                } else {
                    const offcanvasEl = document.getElementById('shoppingCart');
                    if (offcanvasEl) {
                        const offcanvas = new bootstrap.Offcanvas(offcanvasEl);
                        offcanvas.show();
                    }
                    return;
                }
            }

            // Respect qty input if present (e.g. product details page)
            const qtyInput = btn.closest('form, .tf-product-info-list, .card-product').find('.quantity-product, input[name="qty"], input[name="quantity"]');
            const qty = qtyInput.length ? (parseInt(qtyInput.val()) || 1) : 1;

            const payload = {
                id: btn.data('id'),
                name: btn.data('name'),
                price: btn.data('price'),
                image: btn.data('image'),
                url: btn.data('url'),
                qty: qty,
            };

            const selectedAttributes = btn.closest('form, .tf-product-info-list').find('.variant-option:checked').map(function () {
                return parseInt($(this).val());
            }).get();

            if (selectedAttributes && selectedAttributes.length) {
                payload.attributes = selectedAttributes;
            }

            cartAjax(routes.cartAdd, payload).done(function (res) {
                renderMiniCart(res);
                if (typeof toastr !== 'undefined') {
                    toastr.success('Product added to cart!');
                }
                const offcanvas = document.getElementById('shoppingCart');
                if (offcanvas && typeof bootstrap !== 'undefined') {
                    bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show();
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not add to cart. Please try again.');
                }
            });
        });

        // ─── Buy Now (order-now) ─────────────────────────────────────────────
        $(document).on('click', '.order-now', function (e) {
            e.preventDefault();
            const btn = $(this);

            // If product has variants and we're on a product card, redirect to product page
            if (btn.data('has-variant') == '1' && !btn.closest('#quickView, .tf-product-info-list, form').length) {
                const productUrl = btn.data('product-url') || btn.data('url');
                if (productUrl) {
                    window.location.href = productUrl;
                    return;
                }
            }

            const qtyInput = btn.closest('form, .tf-product-info-list, .card-product').find('.quantity-product, input[name="qty"], input[name="quantity"]');
            const qty = qtyInput.length ? (parseInt(qtyInput.val()) || 1) : 1;

            const payload = {
                id: btn.data('id'),
                name: btn.data('name'),
                price: btn.data('price'),
                image: btn.data('image'),
                url: btn.data('url'),
                qty: qty,
            };

            const selectedAttributes = btn.closest('form, .tf-product-info-list').find('.variant-option:checked').map(function () {
                return parseInt($(this).val());
            }).get();

            if (selectedAttributes && selectedAttributes.length) {
                payload.attributes = selectedAttributes;
            }

            cartAjax(routes.cartAdd, payload).done(function (res) {
                if (res.success) {
                    window.location.href = routes.checkout || '/checkout';
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || 'Could not place order. Please try again.');
                    }
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not place order. Please try again.');
                }
            });
        });

        // ─── Remove from Mini Cart ───────────────────────────────────────────
        $(document).on('click', '#shoppingCart .remove', function () {
            const id = $(this).data('cart-id') || $(this).closest('[data-cart-id]').data('cart-id');
            if (!id || !routes.cartRemove) return;

            const url = routes.cartRemove.replace(':id', id);
            cartAjax(url, { _method: 'DELETE' }).done(function (res) {
                renderMiniCart(res);
                if (typeof toastr !== 'undefined') {
                    toastr.info('Item removed from cart.');
                }
            });
        });

        // ─── Wishlist Toggle ────────────────────────────────────────────────────────
        // Handles both: <a data-action="wishlist"> and <button data-action="wishlist" class="wd-action-btn">
        $(document).on('click', '[data-action="wishlist"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const productId = btn.data('id');
            if (!productId || !routes.wishlistAdd) return;

            btn.prop('disabled', true);

            cartAjax(routes.wishlistAdd, { id: productId }).done(function (res) {
                if (res.success) {
                    if (res.action === 'added') {
                        btn.addClass('active wd-action-btn--active');
                        // icon-* style (anchor buttons)
                        btn.find('.icon').removeClass('icon-heart2').addClass('icon-hearth');
                        // bi-* style (wd-action-btn buttons)
                        btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                        btn.find('.tooltip').text('In Wishlist');
                        btn.attr('title', 'Remove from Wishlist');
                        if (typeof toastr !== 'undefined') toastr.success(res.message || 'Added to Wishlist!');
                    } else {
                        btn.removeClass('active wd-action-btn--active');
                        // icon-* style
                        btn.find('.icon').removeClass('icon-hearth').addClass('icon-heart2');
                        // bi-* style
                        btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                        btn.find('.tooltip').text('Add to Wishlist');
                        btn.attr('title', 'Add to Wishlist');
                        if (typeof toastr !== 'undefined') toastr.info(res.message || 'Removed from Wishlist.');
                    }
                    updateWishlistCount(res.count);
                } else {
                    if (typeof toastr !== 'undefined') toastr.error('Could not update wishlist.');
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') toastr.error('Could not update wishlist.');
            }).always(function () {
                btn.prop('disabled', false);
            });
        });

        // ─── Compare Toggle ────────────────────────────────────────────────────────
        // Handles both: <a data-action="compare"> and <button data-action="compare" class="wd-action-btn">
        // NOTE: We stop Bootstrap from auto-opening the offcanvas; we open it manually only on 'added'.
        $(document).on('click', '[data-action="compare"]', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation(); // prevent Bootstrap data-bs-toggle from firing
            const btn = $(this);
            const productId = btn.data('id');
            if (!productId || !routes.compareAdd) return;

            btn.prop('disabled', true);

            const product = getProductDataFromCard(btn);

            cartAjax(routes.compareAdd, { id: productId }).done(function (res) {
                if (res.success) {
                    updateCompareCount(res.count);
                    if (res.action === 'added') {
                        btn.addClass('active wd-action-btn--active');
                        btn.find('.icon').removeClass('icon-compare').addClass('icon-compare1');
                        btn.attr('title', 'Remove from Compare');
                        appendCompareItem(product);
                        if (typeof toastr !== 'undefined') toastr.success(res.message || 'Added to Compare!');
                        // Open the offcanvas ONLY when adding
                        const offcanvas = document.getElementById('compare');
                        if (offcanvas && typeof bootstrap !== 'undefined') {
                            bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show();
                        }
                    } else {
                        btn.removeClass('active wd-action-btn--active');
                        btn.attr('title', 'Add to Compare');
                        removeCompareItem(productId);
                        if (typeof toastr !== 'undefined') toastr.info(res.message || 'Removed from Compare.');
                    }
                } else {
                    if (typeof toastr !== 'undefined') toastr.error('Could not update compare list.');
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') toastr.error('Could not update compare list.');
            }).always(function () {
                btn.prop('disabled', false);
            });
        });

        // ─── Quick View ───────────────────────────────────────────────────────
        $(document).on('click', '.quickview', function (e) {
            const url = $(this).data('product-url');
            if (url) {
                e.preventDefault();
                openQuickView(url);
            }
        });

        // ─── Quick View Variant Selection Change ─────────────────────────────
        $(document).on('change', '#quickView .variant-option', function () {
            updateQuickViewVariantDetails();

            // Update main image if selected option has data-image
            const label = $('#quickView').find('label[for="' + this.id + '"]');
            const imageUrl = label.attr('data-image');
            if (imageUrl) {
                const mainImg = $('#quickView').find('.tf-product-view-main .swiper-slide:first-child img');
                if (mainImg.length) {
                    mainImg.attr('src', imageUrl);
                    mainImg.attr('data-src', imageUrl);
                }
                const mainAnchor = $('#quickView').find('.tf-product-view-main .swiper-slide:first-child a');
                if (mainAnchor.length) {
                    mainAnchor.attr('href', imageUrl);
                }

                const thumbImg = $('#quickView').find('.tf-product-view-thumbs .swiper-slide:first-child img');
                if (thumbImg.length) {
                    thumbImg.attr('src', imageUrl);
                }

                // Update addBtn payload image
                $('#quickView').find('.btn-add-quickview').attr('data-image', imageUrl).data('image', imageUrl);

                // Slide both main & thumbs swiper to 0
                const swiperMain = $('#quickView').find('.tf-product-view-main')[0];
                if (swiperMain && swiperMain.swiper) {
                    swiperMain.swiper.slideTo(0);
                }
                const swiperThumbs = $('#quickView').find('.tf-product-view-thumbs')[0];
                if (swiperThumbs && swiperThumbs.swiper) {
                    swiperThumbs.swiper.slideTo(0);
                }
            }
        });

        // ─── Quick View Quantity ───────────────────────────────────────────
        $(document).on('click', '#quickView .plus-btn', function () {
            const input = $(this).closest('.wg-quantity').find('.quantity-product');
            const maxVal = parseInt(input.attr('max')) || 9999;
            let val = parseInt(input.val()) || 1;
            if (val < maxVal) {
                input.val(val + 1);
            }
        });

        $(document).on('click', '#quickView .minus-btn', function () {
            const input = $(this).closest('.wg-quantity').find('.quantity-product');
            let val = parseInt(input.val()) || 1;
            if (val > 1) {
                input.val(val - 1);
            }
        });

        // ─── Quick View — Add to Cart ─────────────────────────────────────────
        $(document).on('click', '.btn-add-quickview', function (e) {
            e.preventDefault();
            const btn = $(this);
            const modal = $('#quickView');
            const qtyInput = modal.find('.quantity-product');
            const qty = qtyInput.length ? (parseInt(qtyInput.val()) || 1) : 1;

            const payload = {
                id: btn.data('id'),
                name: btn.data('name'),
                price: btn.data('price'),
                image: btn.data('image'),
                url: btn.data('url'),
                qty: qty,
            };

            const selectedAttributes = modal.find('.variant-option:checked').map(function () {
                return parseInt($(this).val());
            }).get();

            if (selectedAttributes && selectedAttributes.length) {
                payload.attributes = selectedAttributes;
            }

            cartAjax(routes.cartAdd, payload).done(function (res) {
                renderMiniCart(res);
                if (typeof toastr !== 'undefined') {
                    toastr.success('Product added to cart!');
                }
                modal.modal('hide');
                const offcanvas = document.getElementById('shoppingCart');
                if (offcanvas && typeof bootstrap !== 'undefined') {
                    bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show();
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not add to cart. Please try again.');
                }
            });
        });

        // ─── Compare Offcanvas — Clear All Products ──────────────────────────
        $(document).on('click', '.tf-compapre-button-clear-all, .tf-compare-button-clear-all', function (e) {
            e.preventDefault();
            if (!routes.compareClear) return;

            cartAjax(routes.compareClear, { _method: 'POST' }).done(function (res) {
                if (res && res.success) {
                    $('.offcanvas-compare .tf-compare-wrap').empty().hide();
                    $('.offcanvas-compare .mini-compare-empty').show();
                    updateCompareCount(0);
                    if (typeof toastr !== 'undefined') {
                        toastr.info('Compare list cleared.');
                    }
                }
            });
        });

        // ─── Quick View — Buy Now ─────────────────────────────────────────────
        $(document).on('click', '.btn-add-quickview-buynow', function (e) {
            e.preventDefault();
            const addBtn = $('#quickView').find('.btn-add-quickview');
            const modal = $('#quickView');
            const qtyInput = modal.find('.quantity-product');
            const qty = qtyInput.length ? (parseInt(qtyInput.val()) || 1) : 1;

            const payload = {
                id: addBtn.data('id'),
                name: addBtn.data('name'),
                price: addBtn.data('price'),
                image: addBtn.data('image'),
                url: addBtn.data('url'),
                qty: qty,
            };

            const selectedAttributes = modal.find('.variant-option:checked').map(function () {
                return parseInt($(this).val());
            }).get();

            if (selectedAttributes && selectedAttributes.length) {
                payload.attributes = selectedAttributes;
            }

            cartAjax(routes.cartAdd, payload).done(function (res) {
                if (res.success) {
                    renderMiniCart(res);
                    modal.modal('hide');
                    window.location.href = routes.checkout || '/checkout';
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || 'Could not place order. Please try again.');
                    }
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not place order. Please try again.');
                }
            });
        });

        // ─── Compare Offcanvas — Remove Item ─────────────────────────────────
        $(document).on('click', '.offcanvas-compare .tf-compare-item .remove', function () {
            const id = $(this).data('compare-id');
            $(this).closest('.tf-compare-item').remove();
            if ($('.offcanvas-compare .tf-compare-item').length === 0) {
                $('.mini-compare-empty').show();
                $('.tf-compare-wrap').hide();
            }
            // Deactivate the matching product card button
            $('[data-action="compare"][data-id="' + id + '"]').each(function () {
                $(this).removeClass('active wd-action-btn--active');
                $(this).find('.icon').removeClass('icon-compare1').addClass('icon-compare');
                $(this).attr('title', 'Add to Compare');
            });
            if (id && routes.compareRemove) {
                const url = routes.compareRemove.replace(':id', id);
                cartAjax(url, { _method: 'DELETE' }).done(function (res) {
                    if (res && res.count !== undefined) {
                        updateCompareCount(res.count);
                    }
                });
            }
        });

        // ─── Wishlist Page — Remove Row ──────────────────────────────────────
        $(document).on('click', '.tf-table-wishlist .remove', function () {
            const btn = $(this);
            const id = btn.data('id') || btn.closest('tr').data('id');
            if (!id || !routes.wishlistRemove) return;
            const url = routes.wishlistRemove.replace(':id', id);
            const row = btn.closest('tr.wishlist-item');
            cartAjax(url, { _method: 'DELETE' }).done(function (res) {
                if (res && res.success) {
                    row.fadeOut(300, function () { $(this).remove(); });
                    updateWishlistCount(res.count);
                    if (typeof toastr !== 'undefined') {
                        toastr.info(res.message || 'Item removed from wishlist.');
                    }
                    // Show empty footer if no rows left
                    if ($('.tf-table-wishlist tbody tr.wishlist-item').length === 0) {
                        $('.tf-table-wishlist tfoot').removeClass('d-none');
                    }
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not remove item. Please try again.');
                }
            });
        });

        // ─── Compare Page — Remove Column ────────────────────────────────────
        $(document).on('click', '.tf-table-compare .remove', function () {
            const btn = $(this);
            const id = btn.data('id');
            if (!id || !routes.compareRemove) return;
            const url = routes.compareRemove.replace(':id', id);
            const colIndex = btn.closest('td').index();
            cartAjax(url, { _method: 'DELETE' }).done(function (res) {
                if (res && res.success) {
                    // Remove the corresponding <td> from every row
                    $('.tf-table-compare tr').each(function () {
                        $(this).find('td').eq(colIndex).remove();
                    });
                    updateCompareCount(res.count);
                    if (typeof toastr !== 'undefined') {
                        toastr.info(res.message || 'Item removed from compare.');
                    }
                    // If no compare items remain, reload to show empty state
                    if ($('.tf-compare-info').length === 0) {
                        location.reload();
                    }
                }
            }).fail(function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not remove item. Please try again.');
                }
            });
        });
    });
})(jQuery);
