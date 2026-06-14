<!-- Footer -->
<footer class="tf-footer">
    <div class="ft-body-wrap">
        <div class="ft-body-inner">
            <div class="container">
                <div class="ft-inner flex-wrap flex-xl-nowrap">
                    <div class="ft-logo">
                        <a href="{{ route('home') }}" class="logo-site">
                            @if($settings && $settings->logo)
                                <img src="{{ asset($settings->logo) }}" alt="{{ $settings->company_name ?? 'Logo' }}">
                            @else
                                <img src="{{asset($filePath)}}/images/logo/logo.svg" alt="Logo">
                            @endif
                        </a>
                    </div>
                    <ul class="ft-link-wrap w-100 tf-grid-layout md-col-2 lg-col-4">
                        <li class="footer-col-block">
                            <h6 class="ft-heading footer-heading-mobile fw-semibold">Get help</h6>
                            <div class="tf-collapse-content">
                                <ul class="ft-menu-list">
                                    <li><a href="{{ route('contacts') }}" class="link">Contact Us</a></li>>
                                    <li><a href="{{ route('privacy.policy') }}" class="link">Privacy Policy</a></li>
                                    <li><a href="{{ route('return.policy') }}" class="link">Return Policy</a></li>
                                    <li><a href="{{ route('terms.conditions') }}" class="link">Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="footer-col-block">
                            <h6 class="ft-heading footer-heading-mobile fw-semibold">Popular categories</h6>
                            <div class="tf-collapse-content">
                                <ul class="ft-menu-list">
                                    @foreach($categories->take(4) as $category)
                                        <li><a href="{{ route('shop', ['category' => $category->slug]) }}" class="link">{{ $category->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li class="footer-col-block">
                            <h6 class="ft-heading footer-heading-mobile fw-semibold">Customer Care</h6>
                            <div class="tf-collapse-content">
                                <ul class="ft-menu-list">
                                    <li><a href="{{ route('my.account') }}" class="link">My Account</a></li>
                                    <li><a href="{{ route('track.order') }}" class="link">Track your Order</a></li>
                                    <li><a href="{{ route('contacts') }}" class="link">Customer Service</a></li>
                                    <li><a href="{{ route('faq') }}" class="link">FAQs</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="footer-col-block type-sp-2">
                            <h6 class="ft-heading footer-heading-mobile fw-semibold">Contact</h6>
                            <div class="tf-collapse-content">
                                <ul class="ft-menu-list ft-contact-list">
                                    <li>
                                        <span class="icon">
                                            <i class="icon-location"></i>
                                        </span>
                                        <a href="#" class="link">
                                            {{ $settings->address ?? '8500 Lorem Street, Chicago, IL 55030' }}
                                        </a>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <i class="icon-phone"></i>
                                        </span>
                                        <a href="tel:{{ $settings->phone ?? '' }}" class="product-title">
                                            <span class="product-title text-primary">
                                                {{ $settings->phone ?? '+8801700000000' }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <i class="icon-direction"></i>
                                        </span>
                                        <a href="mailto:{{ $settings->email ?? '' }}" class="">
                                            <span class="text-primary">
                                                {{ $settings->email ?? 'support@example.com' }}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ft-body-center bg-gray">
            <div class="container">
                <div class="ft-center justify-content-xxl-between">
                    <p class="notice text-white justify-content-xxl-between">
                        <span class="main-title fw-semibold ">
                            <img src="{{asset($filePath)}}/images/mail.svg" alt="">
                            10% Off Your First Order
                        </span>
                        <span class="body-text-3">
                            Be the first to know about offers, new products and discounted products
                        </span>
                    </p>
                    <div class="sib-form">
                        <div id="sib-form-container" class="sib-form-container">
                            <div id="error-message" class="sib-form-message-panel">
                                <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                        <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z"></path>
                                    </svg>
                                    <span class="sib-form-message-panel__inner-text">
                                        Your subscription could not be saved. Please try again.
                                    </span>
                                </div>
                            </div>
                            <div id="success-message" class="sib-form-message-panel">
                                <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                        <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z"></path>
                                    </svg>
                                    <span class="sib-form-message-panel__inner-text">
                                        Your subscription has been successful.
                                    </span>
                                </div>
                            </div>
                            <div id="sib-container" class="sib-container--large sib-container--vertical">
                                <form id="sib-form" method="POST" class="form-newsletter" action="https://3c02c1a1.sibforms.com/serve/MUIFABp-6TRH_ZaK3WSmgGEDN5JKtMVuO6AlfuFlAQ5zTnRMTM9BUGeezu_2xii-Q69nQvGvpnGbcxXU67nGQ5uDYgSnl0-UsYaPkTQENX9KjaNOrfvCz8rpEWGmf8Hp7zP5oS_WBIdftR5tby8mzBWr8pGQ7408FpU45UnQz5_Noqye8awDqyH6FqstWhwflIgsFbsd_AHEpMrk" data-type="subscription" novalidate="true">
                                    <div style="display: none;">
                                        <div class="sib-form-block">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div style="display: none;">
                                        <div class="sib-form-block">
                                            <div class="sib-text-form-block">
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: none;">
                                        <div class="sib-optin sib-form-block">
                                            <div class="form__entry entry_mcq">
                                                <div class="form__label-row ">
                                                    <div class="entry__choice">
                                                        <label>
                                                            <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN">
                                                            <span class="checkbox checkbox_tick_positive"></span>
                                                            <span>
                                                                <p></p>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <label class="entry__error entry__error--primary">
                                                </label>
                                                <label class="entry__specification">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="sib-input sib-form-block">
                                            <div class="form__entry entry_block">
                                                <div class="form__label-row ">
                                                    <label class="entry__label" for="EMAIL">
                                                    </label>
                                                    <div class="entry__field">
                                                        <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="Enter Your Email Address" data-required="true" required="">
                                                    </div>
                                                </div>
                                                <label class="entry__error entry__error--primary"></label>
                                                <label class="entry__specification">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="sib-form-block">
                                            <button class="sib-form-block__button sib-form-block__button-with-loader subscribe-button tf-btn btn-large hover-shine" form="sib-form" type="submit">
                                                <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                                                    <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z"></path>
                                                </svg>
                                                <span class="text-white">Subscribe</span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="text" name="email_address_check" value="" class="input--hidden" aria-hidden="true">
                                    <input type="hidden" name="locale" value="en">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ft-body-bottom">
            <div class="container">
                <div class="ft-bottom">
                    <ul class="social-list">
                        @if($settings && isset($settings->social_links['facebook']) && $settings->social_links['facebook'])
                        <li><a href="{{ $settings->social_links['facebook'] }}"><i class="icon-facebook"></i></a></li>
                        @else
                        <li><a href="https://www.facebook.com/"><i class="icon-facebook"></i></a></li>
                        @endif
                        @if($settings && isset($settings->social_links['twitter']) && $settings->social_links['twitter'])
                        <li><a href="{{ $settings->social_links['twitter'] }}"><i class="icon-x"></i></a></li>
                        @else
                        <li><a href="https://x.com/"><i class="icon-x"></i></a></li>
                        @endif
                        @if($settings && isset($settings->social_links['instagram']) && $settings->social_links['instagram'])
                        <li><a href="{{ $settings->social_links['instagram'] }}"><i class="icon-instagram"></i></a></li>
                        @else
                        <li><a href="https://www.instagram.com/"><i class="icon-instagram"></i></a></li>
                        @endif
                        @if($settings && isset($settings->social_links['linkedin']) && $settings->social_links['linkedin'])
                        <li><a href="{{ $settings->social_links['linkedin'] }}"><i class="icon-linkin"></i></a></li>
                        @else
                        <li><a href="https://www.linkedin.com/"><i class="icon-linkin"></i></a></li>
                        @endif
                        @if($settings && isset($settings->social_links['whatsapp']) && $settings->social_links['whatsapp'])
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->social_links['whatsapp']) }}"><i class="icon-whatapp"></i></a></li>
                        @else
                        <li><a href="https://web.whatsapp.com/"><i class="icon-whatapp"></i></a></li>
                        @endif
                    </ul>
                    <ul class="ft-menu-list-2 body-text-3">
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold">New arrivals</a></li>
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold">Best sale</a></li>
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold">Value of the day</a>
                        </li>
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold">Top 100 offers</a></li>
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold">Blog</a></li>
                        <li><a href="blog-grid.html" class="title-sidebar link fw-bold"><i
                                    class="icon-fire"></i> 50% OFF</a>
                        </li>
                    </ul>
                    <p class="nocopy caption text-center">
                        <span class="fw-medium">{{ $settings->company_name ?? config('app.name') }}.</span>© {{ date('Y') }}. All right reserved
                        @if($settings && $settings->copyright)
                            - {{ $settings->copyright }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /Footer -->
