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
            footer.hide();
            updateCartCount(0);
            return;
        }

        empty.hide();
        footer.show();
        updateCartCount(res.count);

        res.items.forEach(function (item) {
            const image = (item.attributes && item.attributes.image) ? item.attributes.image : (routes.noImage || '');
            const url = (item.attributes && item.attributes.url) ? item.attributes.url : '#';

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

    function openQuickView(url) {
        const modal = $('#quickView');
        if (!modal.length || !url) return;

        modal.find('.product-info-name a').text('Loading...');
        modal.modal('show');

        $.get(url).done(function (html) {
            const doc = $(html);
            const title = doc.find('.product__title').text().trim();
            const price = doc.find('.product__price--new, .product__price--current').first().text().trim()
                || doc.find('.product__prices').text().trim();
            const oldPrice = doc.find('.product__price--old').text().trim();
            const description = doc.find('.product__excerpt, .product__description').first().text().trim();
            const productId = doc.find('input[name="id"]').val() || '';
            const images = [];

            doc.find('.product-gallery__featured img, .product__gallery img, .image__tag').each(function () {
                const src = $(this).attr('src');
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
            }
        }).fail(function () {
            window.location.href = url;
        });
    }

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

            const selectedAttributes = btn.closest('form, .tf-product-info-list').find('.variant-option:checked').map(function() {
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

            const selectedAttributes = btn.closest('form, .tf-product-info-list').find('.variant-option:checked').map(function() {
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

        // ─── Quick View — Add to Cart ─────────────────────────────────────────
        $(document).on('click', '.btn-add-quickview', function (e) {
            e.preventDefault();
            const btn = $(this);
            cartAjax(routes.cartAdd, {
                id: btn.data('id'),
                name: btn.data('name'),
                price: btn.data('price'),
                image: btn.data('image'),
                url: btn.data('url'),
                qty: 1,
            }).done(function (res) {
                renderMiniCart(res);
                if (typeof toastr !== 'undefined') {
                    toastr.success('Product added to cart!');
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
