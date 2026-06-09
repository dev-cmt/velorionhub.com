<x-frontend-layout title="Track Order" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="index.html" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <p class="body-small">
                        Track Your Order
                    </p>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- Track Order -->
    <section class="s-track-order tf-sp-2">
        <div class="container">
            <div class="position-relative">
                <div class="parallax-image">
                    <img src="{{asset($filePath)}}/images/section/parallax-3.jpg" data-src="{{asset($filePath)}}/images/section/parallax-3.jpg" alt=""
                        class="lazyload effect-paralax">
                </div>
                <div class="wrap">
                    <div class="box-title">
                        <h5 class="fw-semibold">Track your order</h5>
                        <p class="body-text-3">To track your order, please enter your order ID in the box below
                            and
                            press the "Track" button. The ID has been sent to you on your receipt and in the
                            confirmation email you received.</p>
                    </div>
                    <form class="form-trackorder def">
                        <fieldset>
                            <label>Oder ID</label>
                            <input class="def" type="text" placeholder="Found in your order confirmation email"
                                required>
                        </fieldset>
                        <fieldset>
                            <label>Order email</label>
                            <input class="def" type="text" placeholder="Email you used during checkout" required>
                        </fieldset>
                        <div class="box-btn">
                            <button type="submit" class="tf-btn w-100">
                                <span class="text-white">Track</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /Track Order -->
</x-frontend-layout>
