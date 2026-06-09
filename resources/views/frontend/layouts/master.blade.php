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

    <!-- Favicon and Touch Icons  -->
    @if($settings && $settings->favicon)
    <link rel="icon" type="image/png" href="{{ asset($settings->favicon) }}">
    <link href="{{ asset($settings->favicon) }}" rel="shortcut icon">
    <link href="{{ asset($settings->favicon) }}" rel="apple-touch-icon-precomposed">
    @else
    <link rel="icon" type="image/png" href="{{asset($filePath)}}/favicon.png">
    <link href="{{asset($filePath)}}/images/logo/short-logo.svg" rel="shortcut icon">
    <link href="{{asset($filePath)}}/images/logo/short-logo.svg" rel="apple-touch-icon-precomposed">
    @endif

    <!-- fonts -->
    <link rel="stylesheet" href="{{asset($filePath)}}/fonts/font.css">
    <link rel="stylesheet" href="{{asset($filePath)}}/icons/icomoon/style.css">
    <!-- css -->
    <link rel="stylesheet" href="{{asset($filePath)}}/css/sib-styles.css">
    <link rel="stylesheet" href="{{asset($filePath)}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset($filePath)}}/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{asset($filePath)}}/css/animate.css">

    @stack('css')
    <link rel="stylesheet" href="{{asset($filePath)}}/css/styles.css" type="text/css">
</head>
<body class="preload-wrapper popup-loader">
    <!-- Scroll Top -->
    <button id="goTop">
        <span class="border-progress"></span>
        <i class="icon icon-arrow-right"></i>
    </button>

    @if($settings && $settings->is_loading)
    <!-- preload -->
    <div class="preload preload-container" id="preload">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->
    @endif

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
    <script src="{{asset($filePath)}}/js/jquery.min.js"></script>
    <script src="{{asset($filePath)}}/js/bootstrap.min.js"></script>
    <script src="{{asset($filePath)}}/js/swiper-bundle.min.js"></script>
    <script src="{{asset($filePath)}}/js/carousel.js"></script>
    <script src="{{asset($filePath)}}/js/bootstrap-select.min.js"></script>
    <script src="{{asset($filePath)}}/js/lazysize.min.js"></script>
    <script src="{{asset($filePath)}}/js/count-down.js"></script>
    <script src="{{asset($filePath)}}/js/wow.min.js"></script>
    <script src="{{asset($filePath)}}/js/multiple-modal.js"></script>
    <script src="{{asset($filePath)}}/js/infinityslide.js"></script>
    <script src="{{asset($filePath)}}/js/main.js"></script>
    <script src="{{asset($filePath)}}/js/storefront.js"></script>
    <script src="{{asset($filePath)}}/js/sibforms.js" defer></script>
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
