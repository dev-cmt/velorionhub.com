<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! $seotags ?? '' !!}
	{!! $breadcrumbs ?? '' !!}
	{!! $jsonld ?? '' !!}

    <link rel="icon" type="image/png" href="{{asset('frontend')}}/favicon.png">
    
    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">
    <!-- css -->
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/owl-carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/photoswipe/photoswipe.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/photoswipe/default-skin/default-skin.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/style-red.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/header-red.css" media="(min-width: 1200px)">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/mobile-red.css" media="(max-width: 1199px)">
    <!-- font - fontawesome -->
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/fontawesome/css/all.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @stack('css')
</head>

<body>
    <!-- site -->
    <div class="site">
        <!-- Start of Header -->
        @include('frontend.partials.navbar')
        <!-- End of Header -->

        <!-- Start of Main -->
        {{ $slot }}
        <!-- End of Main -->

        <!-- Start of Footer -->
        @include('frontend.partials.footer')
        <!-- End of Footer -->
    </div>
    <!-- site / end -->

    <!-- modals -->
    @include('frontend.partials.modals')
    <!-- modals / end -->

    <!-- scripts -->
    <script src="{{asset('frontend')}}/vendor/jquery/jquery.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/owl-carousel/owl.carousel.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/nouislider/nouislider.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe-ui-default.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/select2/js/select2.min.js"></script>
    <script src="{{asset('frontend')}}/js/number.js"></script>
    <script src="{{asset('frontend')}}/js/main.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {

            // GLOBAL AJAX SETUP
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Global AJAX Error Handler
            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                let message = 'Something went wrong. Please try again.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    message = jqXHR.responseJSON.message;
                }
                toastr.error(message);
            });

            // Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // GLOBAL AJAX HELPER
            function cartAjax(url, data = {}, method = 'POST') {
                return $.ajax({ url, method, data });
            }

            // UPDATE MINI CART
            function updateMiniCart(res) {
                $('.ms2_total_count').text(res.count);
                $('#mini-cart-products').html('');

                if (res.count === 0) {
                    $('#mini-cart-products').html('<p class="text-center p-3">Cart is empty</p>');
                    $('#mini-cart-subtotal').text('$0.00');
                    return;
                }

                if (res.items) {
                    res.items.forEach(item => {
                        let image = item.attributes && item.attributes.image ? item.attributes.image : '{{ asset("images/no-image.jpg") }}';
                        let url = item.attributes && item.attributes.url ? item.attributes.url : '#';
                        
                        $('#mini-cart-products').append(`
                            <div class="product product-cart">
                                <div class="product-detail">
                                    <a href="${url}" class="product-name">
                                        ${item.name}
                                    </a>
                                    <div class="price-box">
                                        <span class="product-quantity">${item.quantity}</span>
                                        <span class="product-price">TK ${item.price}</span>
                                    </div>
                                </div>

                                <figure class="product-media">
                                    <a href="${url}">
                                        <img src="${image}" alt="product" width="94" height="84">
                                    </a>
                                </figure>

                                <button class="btn btn-link btn-close remove-cart" data-id="${item.id}" aria-label="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                    });
                }

                $('#mini-cart-subtotal').text('TK ' + res.subtotal);
                
                if (res.message && res.success !== false) {
                    toastr.success(res.message);
                } else if (res.message && res.success === false) {
                    toastr.error(res.message);
                }
            }

            // LOAD CART ON PAGE LOAD
            $.get("{{ route('cart.mini') }}", updateMiniCart);

            // ADD TO CART (Direct Button)
            $(document).on('click', '.btn-cart', function () {
                let btn = $(this);
                let qty = $('.quantity').val() || 1;

                cartAjax("{{ route('cart.add') }}", {
                    _token: "{{ csrf_token() }}",
                    id: btn.data('id'),
                    name: btn.data('name'),
                    price: btn.data('price'),
                    image: btn.data('image'),
                    url: btn.data('url'),
                    qty: qty
                }).done(updateMiniCart);
            });

            // ADD TO CART (Form Submission)
            $(document).on('submit', '.ms2_form', function (e) {
                e.preventDefault();
                let form = $(this);
                let formData = form.serialize();
                
                // If quantity exists in form as 'count', we might want to map it to 'qty'
                // though the controller handles both or defaults.
                
                cartAjax(form.attr('action'), formData).done(updateMiniCart);
            });

            // REMOVE ITEM
            $(document).on('click', '.remove-cart', function () {
                let id = $(this).data('id');
                let url = "{{ route('cart.remove', ':id') }}".replace(':id', id);
                cartAjax(url, { _token: "{{ csrf_token() }}", _method: 'DELETE' }, 'POST')
                    .done(function(res) {
                        updateMiniCart(res);
                        // If we are on the cart page, we need to reload to show changes in the main table
                        if ($('.cart-card').length || window.location.pathname.includes('/cart')) {
                            location.reload();
                        }
                    });
            });

            // INCREASE QTY
            $(document).on('click', '.qty-btn.plus', function () {
                let id = $(this).data('id');
                let qty = parseInt($(this).siblings('.product-quantity').text()) + 1;

                cartAjax("{{ route('cart.update.qty') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    qty: qty
                }).done(updateMiniCart);
            });

            // DECREASE QTY
            $(document).on('click', '.qty-btn.minus', function () {
                let id = $(this).data('id');
                let qty = parseInt($(this).siblings('.product-quantity').text()) - 1;
                if (qty < 1) return;

                cartAjax("{{ route('cart.update.qty') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    qty: qty
                }).done(updateMiniCart);
            });

            // AJAX SEARCH SUGGESTIONS
            let searchTimer = null;

            function handleSearch(input, container) {
                let query = input.val().trim();
                clearTimeout(searchTimer);

                if (query.length < 3) {
                    container.hide().empty();
                    return;
                }

                // Show loading spinner immediately
                container.html('<div class="suggestion-loading"><div class="spinner"></div> Searching...</div>').show();

                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('search.suggest') }}",
                        method: "GET",
                        data: { search: query },
                        success: function(html) {
                            container.html(html).show();
                        },
                        error: function() {
                            container.hide().empty();
                        }
                    });
                }, 320);
            }

            $('#search_desktop').on('input', function() {
                handleSearch($(this), $('#search-suggestions-container'));
            });

            $('#search').on('input', function() {
                handleSearch($(this), $('#search-suggestions-mobile'));
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search, .mobile-search__body').length) {
                    $('#search-suggestions-container, #search-suggestions-mobile').hide();
                }
            });

        });
    </script>
    
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <!--<button class="pswp__button pswp__button--share" title="Share"></button>-->
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    
    @stack('js')
</body>
</html>