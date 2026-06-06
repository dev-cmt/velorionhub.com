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
    <link rel="stylesheet" href="{{asset('frontend')}}/fonts/font.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/icons/icomoon/style.css">
    <!-- css -->
    <link rel="stylesheet" href="{{asset('frontend')}}/css/sib-styles.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/animate.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/styles.css" type="text/css">

    @stack('css')

    <!-- Favicon and Touch Icons  -->
    <link href="{{asset('frontend')}}/images/logo/short-logo.svg" rel="shortcut icon">
    <link href="{{asset('frontend')}}/images/logo/short-logo.svg" rel="apple-touch-icon-precomposed">

</head>
<body class="preload-wrapper popup-loader">
    <!-- Scroll Top -->
    <button id="goTop">
        <span class="border-progress"></span>
        <i class="icon icon-arrow-right"></i>
    </button>

    <!-- preload -->
    <div class="preload preload-container" id="preload">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->

    <div id="wrapper">
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

    <!-- modals -->
    @include('frontend.partials.modals')
    <!-- modals / end -->

    <!-- Javascript -->
    <script src="{{asset('frontend')}}/js/jquery.min.js"></script>
    <script src="{{asset('frontend')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('frontend')}}/js/swiper-bundle.min.js"></script>
    <script src="{{asset('frontend')}}/js/carousel.js"></script>
    <script src="{{asset('frontend')}}/js/bootstrap-select.min.js"></script>
    <script src="{{asset('frontend')}}/js/lazysize.min.js"></script>
    <script src="{{asset('frontend')}}/js/count-down.js"></script>
    <script src="{{asset('frontend')}}/js/wow.min.js"></script>
    <script src="{{asset('frontend')}}/js/multiple-modal.js"></script>
    <script src="{{asset('frontend')}}/js/infinityslide.js"></script>
    <script src="{{asset('frontend')}}/js/main.js"></script>
    <script src="{{asset('frontend')}}/js/storefront.js"></script>
    <script src="{{asset('frontend')}}/js/sibforms.js" defer></script>
    <script>
        window.VelorionRoutes = {
            cartAdd: "{{ route('cart.add') }}",
            cartMini: "{{ route('cart.mini') }}",
            cartRemove: "{{ route('cart.remove', ':id') }}",
            wishlistAdd: "{{ route('wishlist.add') }}",
            compareAdd: "{{ route('compare.add') }}",
            shop: "{{ route('shop') }}",
            cart: "{{ route('cart') }}",
            checkout: "{{ route('checkout') }}",
            compare: "{{ route('compare') }}",
            wishlist: "{{ route('wishlist') }}",
            noImage: "{{ asset('images/no-image.jpg') }}",
        };
    </script>
    <script>
        window.REQUIRED_CODE_ERROR_MESSAGE = 'Please choose a country code';
        window.LOCALE = 'en';
        window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";

        window.REQUIRED_ERROR_MESSAGE = "This field cannot be left blank. ";

        window.GENERIC_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";

        window.translation = {
            common: {
                selectedList: '{quantity} list selected',
                selectedLists: '{quantity} lists selected'
            }
        };
        var AUTOHIDE = Boolean(0);
    </script>
    @stack('js')
</body>
</html>
