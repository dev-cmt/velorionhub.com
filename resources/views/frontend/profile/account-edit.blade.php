<x-frontend-layout title="FAQ" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-1 pb-0">
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
                    <span class="body-small">Account</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- My Account -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account ">
                        <ul class="my-account-nav content-append">
                            <li><a href="{{ route('my.account') }}" class="my-account-nav-item">Dashboard</a></li>
                            <li><a href="{{ route('order.list') }}" class="my-account-nav-item">Orders</a></li>
                            <li><span class="my-account-nav-item active">Account Details</span></li>
                            <li><a href="{{ route('wishlist') }}" class="my-account-nav-item">Wishlist</a></li>
                            <li><a href="{{ route('logout') }}" class="my-account-nav-item">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-details">
                        <div class="wrap">
                            <h4 class="fw-semibold mb-20">Information</h4>
                            <form class="form-account-details">
                                <div class="form-content">
                                    <fieldset>
                                        <input type="text" name="full_name" placeholder="Full Name" value="e.g John Doe">
                                    </fieldset>
                                    <div class="cols">
                                        <fieldset>
                                            <input type="email" name="email" placeholder="Email" value="onsus@support.com">
                                        </fieldset>
                                        <fieldset>
                                            <input type="number" name="phone" placeholder="Phone" value="08801234567">
                                        </fieldset>
                                    </div>
                                    <fieldset>
                                        <textarea name="address" id="" rows="2" placeholder="Address">Australia</textarea>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                        <div class="wrap">
                            <h4 class="fw-semibold mb-20">Change Password</h4>
                            <form class="def form-reset-password">
                                <fieldset>
                                    <input type="password" name="password" placeholder="Password*" required="">
                                </fieldset>
                                <fieldset>
                                    <input type="password" name="new_password" placeholder="New Password*" required="">
                                </fieldset>
                                <fieldset>
                                    <input type="password" name="confirm_password" placeholder="Confirm Password*" required="">
                                </fieldset>
                                <div class="box-btn">
                                    <button type="submit" class="tf-btn btn-large">
                                        <span class="text-white">Update Account</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account -->
</x-frontend-layout>
