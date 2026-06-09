<x-frontend-layout title="Shopping Cart" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="index.html" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Cart</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Shopping Cart -->
    <div class="s-shoping-cart tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar first"></span>
                    <div class="step-payment ">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="shop-cart.html" class="text-secondary body-text-3">Shopping Cart</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="checkout.html" class="link-secondary body-text-3">Shopping & Checkout</a>

                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <a href="order-details.html" class="link-secondary body-text-3">Confirmation</a>
                    </div>
                </div>
            </div>
            <form class="form-discount">
                <div class="overflow-x-auto">
                    <table class="tf-table-page-cart">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tf-cart-item">
                                <td class="tf-cart-item_product">
                                    <a href="#" class="img-box"><img src="images/product/shop-cart-1.jpg"
                                            alt=""></a>
                                    <div class="cart-info">
                                        <a href="#" class="cart-title body-md-2 fw-semibold link">
                                            5Pcs/Lot Trolling Bait
                                            Minnow Fishing Lure 8.37g Bass Crankbait
                                            Tackle Wobbler
                                        </a>
                                        <div class="variant-box">
                                            <p class="body-text-3">Color:</p>
                                            <div class="tf-select">
                                                <select>
                                                    <option selected="selected">Yellow</option>
                                                    <option>Green</option>
                                                    <option>Black</option>
                                                    <option>Red</option>
                                                    <option>Beige</option>
                                                    <option>Pink</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-cart-title="Price" class="tf-cart-item_price ">
                                    <p class="cart-price price-on-sale price-text fw-medium">$22.99</p>
                                </td>
                                <td data-cart-title="Quantity" class="tf-cart-item_quantity">
                                    <div class="wg-quantity">
                                        <span class="btn-quantity btn-decrease">
                                            <i class="icon-minus"></i>
                                        </span>
                                        <input class="quantity-product" type="text" name="number" value="1">
                                        <span class="btn-quantity btn-increase">
                                            <i class="icon-plus"></i>
                                        </span>
                                    </div>
                                </td>
                                <td data-cart-title="Total" class="tf-cart-item_total">
                                    <p class="cart-total total-price price-text fw-medium">$22.99</p>
                                </td>
                                <td data-cart-title="Remove" class="remove-cart text-xxl-end">
                                    <span class="remove icon icon-close link"></span>
                                </td>
                            </tr>
                            <tr class="tf-cart-item">
                                <td class="tf-cart-item_product">
                                    <a href="#" class="img-box"><img src="images/product/shop-cart-2.jpg"
                                            alt=""></a>
                                    <div class="cart-info">
                                        <a href="#" class="cart-title body-md-2 fw-semibold link">
                                            Intel Core i9-12900K Unlocked Desktop Processor - 16 <br
                                                class="d-none d-xl-block"> Cores And 24
                                            Threads
                                        </a>
                                        <div class="variant-box">
                                            <p class="body-text-3">Color:</p>
                                            <div class="tf-select">
                                                <select>
                                                    <option>Yellow</option>
                                                    <option>Green</option>
                                                    <option selected="selected">Black</option>
                                                    <option>Red</option>
                                                    <option>Beige</option>
                                                    <option>Pink</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-cart-title="Price" class="tf-cart-item_price ">
                                    <p class="cart-price price-on-sale price-text fw-medium">$549.99</p>
                                </td>
                                <td data-cart-title="Quantity" class="tf-cart-item_quantity">
                                    <div class="wg-quantity">
                                        <span class="btn-quantity btn-decrease">
                                            <i class="icon-minus"></i>
                                        </span>
                                        <input class="quantity-product" type="text" name="number" value="1">
                                        <span class="btn-quantity btn-increase">
                                            <i class="icon-plus"></i>
                                        </span>
                                    </div>
                                </td>
                                <td data-cart-title="Total" class="tf-cart-item_total">
                                    <p class="cart-total total-price price-text fw-medium">$549.99</p>
                                </td>
                                <td data-cart-title="Remove" class="remove-cart text-xxl-end">
                                    <span class="remove icon icon-close link"></span>
                                </td>
                            </tr>
                            <tr class="tf-cart-item">
                                <td class="tf-cart-item_product">
                                    <a href="#" class="img-box"><img src="images/product/shop-cart-3.jpg"
                                            alt=""></a>
                                    <div class="cart-info">
                                        <a href="#" class="cart-title body-md-2 fw-semibold link">
                                            Xiaomi Redmi Note 9 Pro 6/128GB GLOBAL VERSION <br
                                                class="d-none d-xl-block"> 6.67" Snapdragon 720G By
                                            FedEx
                                        </a>
                                        <div class="variant-box">
                                            <p class="body-text-3">Color:</p>
                                            <div class="tf-select">
                                                <select>
                                                    <option>Yellow</option>
                                                    <option selected="selected">Green</option>
                                                    <option>Black</option>
                                                    <option>Red</option>
                                                    <option>Beige</option>
                                                    <option>Pink</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-cart-title="Price" class="tf-cart-item_price ">
                                    <p class="cart-price price-on-sale price-text fw-medium">$279.71</p>
                                </td>
                                <td data-cart-title="Quantity" class="tf-cart-item_quantity">
                                    <div class="wg-quantity">
                                        <span class="btn-quantity btn-decrease">
                                            <i class="icon-minus"></i>
                                        </span>
                                        <input class="quantity-product" type="text" name="number" value="1">
                                        <span class="btn-quantity btn-increase">
                                            <i class="icon-plus"></i>
                                        </span>
                                    </div>
                                </td>
                                <td data-cart-title="Total" class="tf-cart-item_total">
                                    <p class="cart-total total-price price-text fw-medium">$279.71</p>
                                </td>
                                <td data-cart-title="Remove" class="remove-cart text-xxl-end">
                                    <span class="remove icon icon-close link"></span>
                                </td>
                            </tr>
                            <tr class="tf-cart-item">
                                <td class="tf-cart-item_product">
                                    <a href="#" class="img-box"><img src="images/product/shop-cart-4.jpg"
                                            alt=""></a>
                                    <div class="cart-info">
                                        <a href="#" class="cart-title body-md-2 fw-semibold link">
                                            Lenovo G27Q 27" QHD (2560 x 1440) IPS 165Hz 1ms <br
                                                class="d-none d-xl-block"> FreeSync Premium Gaming
                                            Monitor
                                        </a>
                                        <div class="variant-box">
                                            <p class="body-text-3">Color:</p>
                                            <div class="tf-select">
                                                <select>
                                                    <option>Yellow</option>
                                                    <option>Green</option>
                                                    <option selected="selected">Black</option>
                                                    <option>Red</option>
                                                    <option>Beige</option>
                                                    <option>Pink</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-cart-title="Price" class="tf-cart-item_price ">
                                    <p class="cart-price price-on-sale price-text fw-medium">$199.99</p>
                                </td>
                                <td data-cart-title="Quantity" class="tf-cart-item_quantity">
                                    <div class="wg-quantity">
                                        <span class="btn-quantity btn-decrease">
                                            <i class="icon-minus"></i>
                                        </span>
                                        <input class="quantity-product" type="text" name="number" value="1">
                                        <span class="btn-quantity btn-increase">
                                            <i class="icon-plus"></i>
                                        </span>
                                    </div>
                                </td>
                                <td data-cart-title="Total" class="tf-cart-item_total">
                                    <p class="cart-total total-price price-text fw-medium">$199.99</p>
                                </td>
                                <td data-cart-title="Remove" class="remove-cart text-xxl-end">
                                    <span class="remove icon icon-close link"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="cart-bottom">
                    <div class="ip-discount-code">
                        <input type="text" placeholder="Enter your cupon code" required>
                        <button type="submit" class="tf-btn btn-gray">
                            <span class="text-white">Apply coupon</span>
                        </button>
                    </div>
                    <span class="last-total-price main-title fw-semibold">Total:</span>
                </div>
            </form>
            <div class="box-btn">
                <a href="404-2.html" class="tf-btn btn-gray"><span class="text-white">Continue
                        shopping</span></a>
                <a href="checkout.html" class="tf-btn"><span class="text-white">Proceed to checkout</span></a>
            </div>

        </div>
    </div>
</x-frontend-layout>
