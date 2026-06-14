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
                            <li><span class="my-account-nav-item active">Dashboard</span></li>
                            <li><a href="{{ route('order.list') }}" class="my-account-nav-item">Orders</a></li>
                            <li><a href="{{ route('account.edit') }}" class="my-account-nav-item">Account Details</a></li>
                            <li><a href="{{ route('wishlist') }}" class="my-account-nav-item">Wishlist</a></li>
                            <li><a href="{{ route('logout') }}" class="my-account-nav-item">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-dashboard">
                        <div class="mb_60">
                            <h3 class="fw-semibold mb-20">Hello Themesflat</h3>
                            <p>
                                From your account dashboard you can view your
                                <a class="text-secondary link fw-medium" href="{{ route('order.list') }}">
                                    recent orders
                                </a>
                                , and
                                <a class="text-secondary link fw-medium" href="{{ route('wishlist') }}">
                                    view your wishlist
                                </a>
                                , and
                                <a class="text-secondary link fw-medium" href="{{ route('account.edit') }}">
                                    edit your password and account details
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account -->
</x-frontend-layout>
