<x-frontend-layout title="Wishlist" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small"> Wishlist</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- Wishlist -->
    <div class="tf-sp-2">
        <div class="container">
            <div class="tf-wishlist">
                <table class="tf-table-wishlist">
                    <thead>
                        <tr>
                            <th class="wishlist-item_remove"></th>
                            <th class="wishlist-item_image"></th>
                            <th class="wishlist-item_info">
                                <p class="product-title fw-semibold">Product Name</p>
                            </th>
                            <th class="wishlist-item_price">
                                <p class="product-title fw-semibold">Unit Price</p>
                            </th>
                            <th class="wishlist-item_stock">
                                <p class="product-title fw-semibold">Stock Status</p>
                            </th>
                            <th class="wishlist-item_action"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="wishlist-item">
                            <td class="wishlist-item_remove">
                                <i class="icon-close remove link cs-pointer"></i>
                            </td>
                            <td class="wishlist-item_image">
                                <a href="product-detail.html">
                                    <img src="images/product/product-165.jpg"
                                        data-src="images/product/product-165.jpg" alt="Image" class="lazyload">
                                </a>
                            </td>
                            <td class="wishlist-item_info">
                                <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                    href="product-detail.html">
                                    Samsung Galaxy S10+, 128GB, Ceramic Black - Unlocked
                                </a>
                            </td>
                            <td class="wishlist-item_price">
                                <p class="price-wrap fw-medium flex-nowrap">
                                    <span class="new-price price-text fw-medium mb-0">$80.000</span>
                                    <span class="old-price body-md-2 text-main-2 fw-normal">$100.000</span>
                                </p>
                            </td>
                            <td class="wishlist-item_stock">
                                <span class="wishlist-stock-status">In Stock</span>
                            </td>
                            <td class="wishlist-item_action">
                                <a href="#shoppingCart" data-bs-toggle="offcanvas" class="tf-btn btn-gray">
                                    <span class="text-white">Add To Cart</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="wishlist-item">
                            <td class="wishlist-item_remove">
                                <i class="icon-close remove link cs-pointer"></i>
                            </td>
                            <td class="wishlist-item_image">
                                <a href="product-detail.html">
                                    <img src="images/product/product-43.jpg"
                                        data-src="images/product/product-43.jpg" alt="Image" class="lazyload">
                                </a>
                            </td>
                            <td class="wishlist-item_info">
                                <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                    href="product-detail.html">
                                    TCL 32-inch 3-Series 720p Roku Smart TV - 32S335, 2021 Model
                                </a>
                            </td>
                            <td class="wishlist-item_price">
                                <p class="price-wrap fw-medium flex-nowrap">
                                    <span class="new-price price-text fw-medium mb-0">$80.000</span>
                                    <span class="old-price body-md-2 text-main-2 fw-normal">$100.000</span>
                                </p>
                            </td>
                            <td class="wishlist-item_stock">
                                <span class="wishlist-stock-status">In Stock</span>
                            </td>
                            <td class="wishlist-item_action">
                                <a href="#shoppingCart" data-bs-toggle="offcanvas" class="tf-btn btn-gray">
                                    <span class="text-white">Add To Cart</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="wishlist-item">
                            <td class="wishlist-item_remove">
                                <i class="icon-close remove link cs-pointer"></i>
                            </td>
                            <td class="wishlist-item_image">
                                <a href="product-detail.html">
                                    <img src="images/product/product-137.jpg"
                                        data-src="images/product/product-137.jpg" alt="Image" class="lazyload">
                                </a>
                            </td>
                            <td class="wishlist-item_info">
                                <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                    href="product-detail.html">
                                    NEW Microsoft Surface Mobile Mouse - Ice Blue
                                </a>
                            </td>
                            <td class="wishlist-item_price">
                                <p class="price-wrap fw-medium flex-nowrap">
                                    <span class="new-price price-text fw-medium mb-0">$80.000</span>
                                    <span class="old-price body-md-2 text-main-2 fw-normal">$100.000</span>
                                </p>
                            </td>
                            <td class="wishlist-item_stock">
                                <span class="wishlist-stock-status">In Stock</span>
                            </td>
                            <td class="wishlist-item_action">
                                <a href="#shoppingCart" data-bs-toggle="offcanvas" class="tf-btn btn-gray">
                                    <span class="text-white">Add To Cart</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="wishlist-item">
                            <td class="wishlist-item_remove">
                                <i class="icon-close remove link cs-pointer"></i>
                            </td>
                            <td class="wishlist-item_image">
                                <a href="product-detail.html">
                                    <img src="images/product/product-86.jpg"
                                        data-src="images/product/product-86.jpg" alt="Image" class="lazyload">
                                </a>
                            </td>
                            <td class="wishlist-item_info">
                                <a class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link"
                                    href="product-detail.html">
                                    YSSOA FNGAMECHAIR01 Gaming Office High Back Computer
                                </a>
                            </td>
                            <td class="wishlist-item_price">
                                <p class="price-wrap fw-medium flex-nowrap">
                                    <span class="new-price price-text fw-medium mb-0">$80.000</span>
                                    <span class="old-price body-md-2 text-main-2 fw-normal">$100.000</span>
                                </p>
                            </td>
                            <td class="wishlist-item_stock">
                                <span class="wishlist-stock-status">In Stock</span>
                            </td>
                            <td class="wishlist-item_action">
                                <a href="#shoppingCart" data-bs-toggle="offcanvas" class="tf-btn btn-gray">
                                    <span class="text-white">Add To Cart</span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="d-none">
                        <tr>
                            <td colspan="6" class="text-center">
                                No products added to the wishlist
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- /Wishlist -->
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
