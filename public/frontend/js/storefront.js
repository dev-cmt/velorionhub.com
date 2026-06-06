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
            data: Object.assign({ _token: csrf() }, data || {}),
        });
    }

    function updateCartCount(count) {
        $('.cart-count, .nav-cart .count-box, a[href="#shoppingCart"] .count-box').text(count);
    }

    function updateWishlistCount(count) {
        $('.wishlist-count, a[href*="wishlist"] .count-box').text(count);
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

    function loadMiniCart() {
        if (!routes.cartMini) return;
        $.get(routes.cartMini).done(renderMiniCart);
    }

    function loadWishlistCount() {
        if (!routes.wishlistAdd) return;
        $.post(routes.wishlistAdd, { _token: csrf() }).done(function (count) {
            updateWishlistCount(count);
        });
    }

    function loadCompareCount() {
        if (!routes.compareAdd) return;
        $.post(routes.compareAdd, { _token: csrf() }).done(function (count) {
            $('.compare-count').text(count);
        });
    }

    function getProductDataFromCard($el) {
        const card = $el.closest('.card-product');
        const addBtn = card.find('.add-to-cart').first();
        return {
            id: addBtn.data('id'),
            name: addBtn.data('name'),
            price: addBtn.data('price'),
            image: addBtn.data('image'),
            url: addBtn.data('url'),
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

        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();
            const btn = $(this);
            const payload = {
                id: btn.data('id'),
                name: btn.data('name'),
                price: btn.data('price'),
                image: btn.data('image'),
                url: btn.data('url'),
                qty: 1,
            };

            cartAjax(routes.cartAdd, payload).done(function (res) {
                renderMiniCart(res);
                const offcanvas = document.getElementById('shoppingCart');
                if (offcanvas && typeof bootstrap !== 'undefined') {
                    bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show();
                }
            });
        });

        $(document).on('click', '#shoppingCart .remove', function () {
            const id = $(this).data('cart-id') || $(this).closest('[data-cart-id]').data('cart-id');
            if (!id || !routes.cartRemove) return;

            const url = routes.cartRemove.replace(':id', id);
            cartAjax(url, { _method: 'DELETE' }).done(renderMiniCart);
        });

        $(document).on('click', '[data-action="wishlist"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const productId = btn.data('id');

            cartAjax(routes.wishlistAdd, { id: productId }).done(function (res) {
                if (res.success) {
                    btn.toggleClass('active');
                    updateWishlistCount(res.count);
                }
            });
        });

        $(document).on('click', '[data-action="compare"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const product = getProductDataFromCard(btn);

            cartAjax(routes.compareAdd, { id: product.id }).done(function (res) {
                if (res.success) {
                    btn.addClass('active');
                    appendCompareItem(product);
                    $('.compare-count').text(res.count);
                    const offcanvas = document.getElementById('compare');
                    if (offcanvas && typeof bootstrap !== 'undefined') {
                        bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show();
                    }
                }
            });
        });

        $(document).on('click', '.quickview', function (e) {
            const url = $(this).data('product-url');
            if (url) {
                e.preventDefault();
                openQuickView(url);
            }
        });

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
            }).done(renderMiniCart);
        });

        $(document).on('click', '.offcanvas-compare .tf-compare-item .remove', function () {
            $(this).closest('.tf-compare-item').remove();
            if ($('.offcanvas-compare .tf-compare-item').length === 0) {
                $('.mini-compare-empty').show();
                $('.tf-compare-wrap').hide();
            }
        });
    });
})(jQuery);
