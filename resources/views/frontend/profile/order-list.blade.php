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
                            <li><a href="my-account-orders.html" class="my-account-nav-item">Orders</a></li>
                            <li><a href="my-account-edit.html" class="my-account-nav-item">Account Details</a></li>
                            <li><a href="wishlist.html" class="my-account-nav-item">Wishlist</a></li>
                            <li><a href="index.html" class="my-account-nav-item">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-dashboard">
                        <h4 class="fw-semibold mb-20">Order History</h4>
                        <div class="tf-order_history-table">
                            <table class="table_def">
                                <thead>
                                    <tr>
                                        <th class="title-sidebar fw-medium">Order ID</th>
                                        <th class="title-sidebar fw-medium">Date</th>
                                        <th class="title-sidebar fw-medium">Status</th>
                                        <th class="title-sidebar fw-medium">Total</th>
                                        <th class="title-sidebar fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="td-order-item">
                                        <td class="body-text-3">#12345</td>
                                        <td class="body-text-3">15 May 2024 </td>
                                        <td class="body-text-3 text-delivered">Delivered</td>
                                        <td class="body-text-3">$690 / 3 items</td>
                                        <td><a href="order-details.html" class="tf-btn btn-small d-inline-flex">
                                                <span class="text-white">Detail</span>

                                            </a></td>
                                    </tr>
                                    <tr class="td-order-item">
                                        <td class="body-text-3">#12345</td>
                                        <td class="body-text-3">15 May 2024 </td>
                                        <td class="body-text-3 text-delivered">Delivered</td>
                                        <td class="body-text-3">$690 / 3 items</td>
                                        <td><a href="order-details.html" class="tf-btn btn-small d-inline-flex">
                                                <span class="text-white">Detail</span>

                                            </a></td>
                                    </tr>
                                    <tr class="td-order-item">
                                        <td class="body-text-3">#12345</td>
                                        <td class="body-text-3">15 May 2024 </td>
                                        <td class="body-text-3 text-on-the-way">On The Way</td>
                                        <td class="body-text-3">$690 / 3 items</td>
                                        <td><a href="order-details.html" class="tf-btn btn-small d-inline-flex">
                                                <span class="text-white">Detail</span>

                                            </a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account -->
</x-frontend-layout>
