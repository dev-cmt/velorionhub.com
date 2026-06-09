<x-frontend-layout title="Checkout" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                    <span class="body-small"> Check Out</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Check Out Cart -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar next"></span>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="shop-cart.html" class="link body-text-3">Shopping Cart</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="checkout.html" class="text-secondary link body-text-3">Shopping & Checkout</a>

                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <a href="order-details.html" class="link body-text-3">Confirmation</a>
                    </div>
                </div>
            </div>
            <div class="tf-checkout-wrap flex-lg-nowrap">
                <div class="page-checkout">
                    <div class="wrap">
                        <h5 class="title has-account">
                            <span class="fw-semibold">Contact</span>
                            <span class="body-text-3">Have an account? <a href="#register" data-bs-toggle="modal"
                                    class="body-text-3 text-secondary link">Login</a></span>

                        </h5>
                        <form class="form-checkout-contact">
                            <label class="body-md-2 fw-semibold">Email or Phone</label>
                            <input class="def" type="text" placeholder="Your contact" required>
                            <p class="caption text-main-2 font-2">Order information will be sent to your email</p>
                        </form>
                    </div>
                    <div class="wrap">
                        <h5 class="title fw-semibold">
                            Delivery
                        </h5>
                        <form class="def">
                            <fieldset>
                                <label>Country/Region</label>
                                <div class="tf-select">
                                    <select>
                                        <option selected>Select your Country/Region</option>
                                        <option>American</option>
                                    </select>
                                </div>
                            </fieldset>
                            <div class="cols">
                                <fieldset>
                                    <label>First name</label>
                                    <input type="text" placeholder="e.g. Jonn" required>
                                </fieldset>
                                <fieldset>
                                    <label>Last name</label>
                                    <input type="text" placeholder="e.g. Doe" required>
                                </fieldset>
                            </div>
                            <div class="cols">
                                <fieldset>
                                    <label>City</label>
                                    <input type="text" placeholder="e.g. New York" required>
                                </fieldset>
                                <fieldset>
                                    <label>State</label>
                                    <div class="tf-select">
                                        <select>
                                            <option selected>Select</option>
                                            <option>Alabam</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Georgia</option>
                                            <option>Washington</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label>ZIP code</label>
                                    <input type="text" placeholder="e.g. 83254" required>
                                </fieldset>
                            </div>
                            <fieldset>
                                <label>Address</label>
                                <input type="email" placeholder="Your detailed address" required>
                            </fieldset>
                            <fieldset>
                                <label>Order note</label>
                                <textarea placeholder="Note on your order"></textarea>
                            </fieldset>
                        </form>
                    </div>
                    <div class="wrap">
                        <h5 class="title">
                            Payment
                        </h5>
                        <form class="form-payment">
                            <div class="payment-box" id="payment-box">
                                <div class="payment-item payment-choose-card active">
                                    <label for="credit-card-method" class="payment-header" data-bs-toggle="collapse"
                                        data-bs-target="#credit-card-payment" aria-controls="credit-card-payment"
                                        aria-expanded="true">
                                        <span class="body-md-2 fw-semibold title">Select payment method</span>
                                        <input type="radio" name="payment-method" class="d-none tf-check-rounded"
                                            id="credit-card-method" checked="">
                                        <p class="select-payment">
                                            Mastercard
                                        </p>
                                    </label>
                                    <div id="credit-card-payment" class="collapse show"
                                        data-bs-parent="#payment-box">
                                        <div class="payment-body">
                                            <fieldset>
                                                <label>Credit Card number</label>
                                                <input type="text" class="number-credit-card"
                                                    placeholder="xxxx   xxxx   xxxx   xxxx">
                                            </fieldset>
                                            <div class="cols">
                                                <fieldset>
                                                    <label>Expiration date</label>
                                                    <input type="date">
                                                </fieldset>
                                                <fieldset>
                                                    <label>CVV</label>
                                                    <input type="number" placeholder="xxx">
                                                </fieldset>
                                            </div>
                                            <fieldset>
                                                <label>Name on card</label>
                                                <input type="text" placeholder="e.g. JOHNDOE">
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-item">
                                    <label for="delivery-method" class="payment-header radio-item collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#delivery-payment"
                                        aria-controls="delivery-payment" aria-expanded="false">
                                        <input type="radio" name="payment-method" class="tf-check-rounded"
                                            id="delivery-method">
                                        <span class="body-text-3">Cash on delivery</span>
                                    </label>
                                    <div id="delivery-payment" class="collapse" data-bs-parent="#payment-box"></div>
                                </div>
                            </div>
                            <div class="box-btn">
                                <a href="order-details.html" class="tf-btn w-100">
                                    <span class="text-white">Place order</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="flat-sidebar-checkout">
                    <div class="sidebar-checkout-content">
                        <h5 class="fw-semibold">Order Summary</h5>
                        <ul class="list-product">
                            <li class="item-product">
                                <a href="#" class="img-product">
                                    <img src="images/product/sc-2.jpg" alt="">
                                </a>
                                <div class="content-box">
                                    <a href="#" class="link-secondary body-md-2 fw-semibold">
                                        Audio-Technica ATH-AD700X Audiophile Open-Air
                                    </a>
                                    <p class="price-quantity price-text fw-semibold">
                                        $80.000
                                        <span class="body-md-2 text-main-2 fw-normal">X1</span>
                                    </p>
                                    <p class="body-md-2 text-main-2">Gray</p>
                                </div>
                            </li>
                        </ul>
                        <div class="">
                            <p class="body-md-2 fw-semibold sub-type">Discount code</p>
                            <form class="ip-discount-code style-2">
                                <input type="text" class="def" placeholder="Your code" required="">
                                <button type="submit" class="tf-btn btn-gray-2">
                                    <span>Apply</span>
                                </button>
                            </form>
                        </div>
                        <ul class="sec-total-price">
                            <li><span class="body-text-3">Sub total</span><span class="body-text-3">$80.000</span>
                            </li>
                            <li><span class="body-text-3">Shipping</span><span class="body-text-3">Free
                                    shipping</span></li>
                            <li><span class="body-md-2 fw-semibold">Total</span><span
                                    class="body-md-2 fw-semibold text-primary">$80.000</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Check Out Cart -->
</x-frontend-layout>
