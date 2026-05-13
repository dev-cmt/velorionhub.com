<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Data -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ $title ?? config('app.name', 'Laravel') }}</title> --}}

    {!! $seotags ?? '' !!}
	{!! $breadcrumbs ?? '' !!}
	{!! $jsonld ?? '' !!}

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{asset('frontend')}}/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: {
                families: ['Poppins:400,500,600,700']
            }
        };
        (function(d) {
            var wf = d.createElement('script'),
                s = d.scripts[0];
            wf.src = '{{asset('frontend')}}/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <link rel="preload" href="{{asset('frontend')}}/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="{{asset('frontend')}}/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="{{asset('frontend')}}/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="{{asset('frontend')}}/fonts/wolmart87d5.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/animate/animate.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/photoswipe/photoswipe.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/vendor/photoswipe/default-skin/default-skin.min.css">

    <!-- Default CSS -->
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/css/style.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{asset('frontend')}}/css/custome.css">

    @stack('css')
</head>

<body>
    <div class="page-wrapper">
        <!-- Start of Header -->
        @include('frontend.partials.navbar')
        <!-- End of Header -->


        <!-- Start of Main -->
         <main class="main">
            {{ $slot }}
        </main>
        <!-- End of Main -->

        <!-- Start of Footer -->
        @include('frontend.partials.footer')
        <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->

    @include('frontend.partials.theme-switcher')

    <!-- Root element of PhotoSwipe. Must have class pswp -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe. It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
			PhotoSwipe keeps only 3 of them in the DOM to save memory.
			Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>

                    <div class="pswp__preloader">
                        <div class="loading-spin"></div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                <button class="pswp__button--arrow--right" aria-label="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of PhotoSwipe -->

    <!-- Plugin JS File -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="{{asset('frontend')}}/vendor/jquery/jquery.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/sticky/sticky.js"></script>
    <script src="{{asset('frontend')}}/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="{{asset('frontend')}}/vendor/zoom/jquery.zoom.js"></script>
    <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe.js"></script>
    <script src="{{asset('frontend')}}/vendor/photoswipe/photoswipe-ui-default.js"></script>

    <!-- Main JS File -->
    <script src="{{asset('frontend')}}/js/main.min.js"></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
    <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c189c47cdb6a138',t:'MTc2OTAxNzM3MA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/d251aa49a8a3/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
    
    <script>
        $(document).ready(function () {

            // GLOBAL AJAX HELPER
            function cartAjax(url, data = {}, method = 'POST') {
                return $.ajax({ url, method, data });
            }

            // UPDATE MINI CART (inline, no separate function)
            function updateMiniCart(res) {
                $('.cart-count').text(res.count);
                $('#mini-cart-products').html('');

                if (res.count === 0) {
                    $('#mini-cart-products').html('<p class="text-center p-3">Cart is empty</p>');
                    $('#mini-cart-subtotal').text('$0.00');
                    return;
                }

                res.items.forEach(item => {
                    $('#mini-cart-products').append(`
                        <div class="product product-cart">
                            <div class="product-detail">
                                <a href="${item.attributes.url}" class="product-name">
                                    ${item.name}
                                </a>
                                <div class="price-box">
                                    <span class="product-quantity">${item.quantity}</span>
                                    <span class="product-price">$${item.price}</span>
                                </div>
                            </div>

                            <figure class="product-media">
                                <a href="${item.attributes.url}">
                                    <img src="${item.attributes.image}" alt="product" width="94" height="84">
                                </a>
                            </figure>

                            <button class="btn btn-link btn-close remove-cart" data-id="${item.id}" aria-label="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                });

                $('#mini-cart-subtotal').text('$' + res.subtotal);
            }

            // LOAD CART ON PAGE LOAD
            $.get("{{ route('cart.mini') }}", updateMiniCart);

            // ADD TO CART
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

            // REMOVE ITEM
            $(document).on('click', '.remove-cart', function () {
                let id = $(this).data('id');
                cartAjax(`/cart/remove/${id}`, { _token: "{{ csrf_token() }}" }, 'DELETE')
                    .done(updateMiniCart);
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

        });
    </script>

    
    @stack('js')
</html>