<x-frontend-layout title="{{ $product->name }}" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    @push('css')
        <link rel="stylesheet" href="{{asset($filePath)}}/css/photoswipe.css">
        <link rel="stylesheet" href="{{asset($filePath)}}/css/drift-basic.min.css">
    @endpush

    <!-- Breakcrumbs -->
    <div class="tf-sp-1">
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
                    <a href="{{ route('shop') }}" class="body-small link">
                        Shop
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">{{ $product->name }}</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Product Main -->
    <section>
        <div class="tf-main-product section-image-zoom border-bt">
            <div class="container">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Product Image -->
                        <div class="tf-product-media-wrap thumbs-left sticky-top">
                            <div class="thumbs-slider flex-xl-row flex-column-reverse">
                                <div class="swiper tf-product-media-thumbs other-image-zoom"
                                    data-direction="vertical">
                                    <div class="swiper-wrapper stagger-wrap">
                                        <div class="swiper-slide stagger-item" data-color="gray">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-14.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-14.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="swiper-slide stagger-item" data-color="gray">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-15.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-15.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="swiper-slide stagger-item" data-color="gray">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-16.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-16.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="swiper-slide stagger-item" data-color="gray">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-17.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-17.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="swiper-slide stagger-item" data-color="beige">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-18.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-18.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="swiper-slide stagger-item" data-color="beige">
                                            <div class="item">
                                                <img class="lazyload"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-19.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-19.jpg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper tf-product-media-main" id="gallery-swiper-started">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide" data-color="gray">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-14.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    src="{{asset($filePath)}}/images/product/product-detail-14.jpg"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-14.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-14.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="swiper-slide" data-color="gray">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-15.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-15.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-15.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-15.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="swiper-slide" data-color="gray">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-16.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-16.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-16.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-16.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="swiper-slide" data-color="gray">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-17.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-17.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-17.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-17.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="swiper-slide" data-color="beige">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-18.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-18.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-18.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-18.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="swiper-slide" data-color="beige">
                                            <a href="{{asset($filePath)}}/images/product/product-detail-19.jpg" target="_blank"
                                                class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                <img class="tf-image-zoom lazyload"
                                                    data-zoom="{{asset($filePath)}}/images/product/product-detail-19.jpg"
                                                    data-src="{{asset($filePath)}}/images/product/product-detail-19.jpg"
                                                    src="{{asset($filePath)}}/images/product/product-detail-19.jpg" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Product Image -->
                    </div>
                    <div class="col-md-6">
                        <!-- Product Infor -->
                        <div class="tf-product-info-wrap position-relative">
                            <div class="tf-zoom-main"></div>
                            <div class="tf-product-info-list style-2">
                                <div class="tf-product-info-content">
                                    <div class="infor-heading">
                                        <p class="caption">Categories:
                                            <a href="shop-default.html" class="link text-secondary">
                                                Consumer Electronics
                                            </a>
                                        </p>
                                        <h5 class="product-info-name fw-semibold">
                                            Elite Gourmet EKT1001B Electric BPA-Free <br class="d-none d-xxl-block">
                                            Glass Kettle, Cordless 360°
                                            Base
                                        </h5>
                                        <ul class="product-info-rate-wrap">
                                            <li class="star-review">
                                                <ul class="list-star">
                                                    <li>
                                                        <i class="icon-star"></i>
                                                    </li>
                                                    <li>
                                                        <i class="icon-star"></i>
                                                    </li>
                                                    <li>
                                                        <i class="icon-star"></i>
                                                    </li>
                                                    <li>
                                                        <i class="icon-star"></i>
                                                    </li>
                                                    <li>
                                                        <i class="icon-star text-main-4"></i>
                                                    </li>
                                                </ul>
                                                <p class="caption text-main-2">Reviews (1.738)</p>
                                            </li>
                                            <li>
                                                <p class="caption text-main-2">Sold: 349</p>
                                            </li>
                                            <li class="d-flex">
                                                <a href="shop-default.html" class="caption text-secondary link">View
                                                    shop</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="infor-center">
                                        <div class="product-info-price">
                                            <h4 class="text-primary">$18.99</h4>
                                            <span class="price-text text-main-2 old-price">$20.99</span>
                                        </div>
                                        <div class="product-delivery">
                                            <p>
                                                <i class="icon-delivery-2"></i>
                                                Free shipping
                                            </p>
                                            <div class="shipping-to">
                                                <p class="body-md-2">
                                                    Shipping to:
                                                </p>
                                                <div class="tf-cur">
                                                    <div class="tf-cur-item">
                                                        <select
                                                            class="select-default cs-pointer fw-semibold body-md-2">
                                                            <option selected="">Metro Manila</option>
                                                            <option>Metro Manila </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="product-fearture-list">
                                        <li>
                                            <p class="body-md-2 fw-semibold">Brand</p>
                                            <span class="body-text-3">Elite Gourmet</span>
                                        </li>
                                        <li>
                                            <p class="body-md-2 fw-semibold">Color</p>
                                            <span class="body-text-3">Black</span>
                                        </li>
                                        <li>
                                            <p class="body-md-2 fw-semibold">Capacity</p>
                                            <span class="body-text-3">1 Liters</span>
                                        </li>
                                        <li>
                                            <p class="body-md-2 fw-semibold">Material</p>
                                            <span class="body-text-3">Glass</span>
                                        </li>
                                        <li>
                                            <p class="body-md-2 fw-semibold">Wattage</p>
                                            <span class="body-text-3">1100 watts</span>
                                        </li>
                                    </ul>
                                    <div class="">
                                        <div class="tf-product-info-choose-option flex-xl-nowrap">
                                            <div class="product-quantity">
                                                <p class=" title body-text-3">
                                                    Quantity
                                                </p>
                                                <div class="wg-quantity">
                                                    <button class="btn-quantity btn-decrease">
                                                        <i class="icon-minus"></i>
                                                    </button>
                                                    <input class="quantity-product" type="text" name="number"
                                                        value="1">
                                                    <button class="btn-quantity btn-increase">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-color">
                                                <p class=" title body-text-3">
                                                    Color
                                                </p>
                                                <div class="tf-select-color ">
                                                    <select class="select-color">
                                                        <option selected="">Graphite Black</option>
                                                        <option>Graphite Blue </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="product-box-btn">
                                                <a href="#shoppingCart" data-bs-toggle="offcanvas"
                                                    class="tf-btn text-white">
                                                    Add to cart
                                                    <i class="icon-cart-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- /Product Infor -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Product Main -->

    <!-- Product Description Tab -->
    <section class="tf-sp-4 ">
        <div class="container">
            <div class="flat-product-des-list style-2">
                <div class="flat-title-tab-product-des">
                    <h5 class=" fw-semibold">
                        Description
                    </h5>
                    <div class="tab-main tab-des">
                        <p class="body-text-3">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tristique nisi id leo
                            mollis egestas. Ut ac ante tincidunt dolor viverra vestibulum. Fusce eget pharetra
                            lorem. Pellentesque ac feugiat nisi. Nulla sollicitudin cursus neque, dapibus
                            aliquet nulla congue congue. In eget sagittis metus, nec semper tortor. Etiam in
                            nunc dui. Sed nibh ante, maximus eu commodo ac, mattis quis elit. Maecenas cursus
                            libero et risus sollicitudin mollis. Sed ultricies sagittis sem, vel iaculis sapien
                            dapibus non. Vivamus facilisis, diam et condimentum sagittis, lectus enim iaculis
                            ipsum, eu finibus urna tellus sit amet ex. Aliquam eget rhoncus lorem. Duis ut metus
                            eget sapien lobortis varius id vel arcu. Sed hendrerit, arcu eget ullamcorper
                            efficitur, enim magna tempus erat, id pretium libero ligula vitae tortor. Aliquam
                            vehicula eleifend sem nec maximus. Aenean ultricies ipsum et laoreet tincidunt.
                        </p>
                        <div class="image">
                            <img src="{{asset($filePath)}}/images/product/description-3.jpg" data-src="{{asset($filePath)}}/images/product/description-3.jpg"
                                alt="" class="lazyload">
                        </div>
                        <p class="body-text-3">
                            Morbi interdum purus id justo pellentesque feugiat. Sed malesuada facilisis enim,
                            volutpat ultrices nulla commodo ut. Proin pulvinar pharetra lacinia. Nulla massa
                            massa, elementum vel gravida nec, fermentum vel risus. Cras eu ipsum id metus
                            sollicitudin scelerisque. Maecenas libero dui, faucibus vel pharetra non, eleifend
                            sit amet felis. Etiam metus nibh, auctor non orci in, consectetur pretium enim
                        </p>
                        <div class="image">
                            <img src="{{asset($filePath)}}/images/product/description-4.jpg" data-src="{{asset($filePath)}}/images/product/description-4.jpg"
                                alt="" class="lazyload">
                        </div>
                        <p class="body-text-3">
                            Pellentesque quis efficitur leo. Maecenas accumsan est in nibh interdum, quis
                            dignissim neque scelerisque. Ut suscipit et leo sit amet lacinia. Sed a laoreet leo,
                            ut tristique risus. Integer a est ut est semper fermentum nec quis nunc. Phasellus
                            aliquam neque eget quam gravida, quis venenatis turpis tristique. Mauris id congue
                            augue. Pellentesque hendrerit porttitor purus, vel porttitor sem blandit vel. Ut
                            auctor, nibh tempus volutpat porttitor, urna ligula gravida lacus, non mollis purus
                            neque ac lorem. Morbi sodales convallis laoreet. Mauris efficitur convallis odio sed
                            congue.
                        </p>
                    </div>
                </div>
                <div class="flat-title-tab-product-des">
                    <h5 class=" fw-semibold">
                        Product information
                    </h5>
                    <div class="tab-main tab-info">
                        <ul class="list-feature">
                            <li>
                                <p class="name-feature">Package Dimensions</p>
                                <p class="property">8 x 8 x 6.7 inches</p>
                            </li>
                            <li>
                                <p class="name-feature">Item Weight</p>
                                <p class="property">2.2 pounds</p>
                            </li>
                            <li>
                                <p class="name-feature">Manufacturer</p>
                                <p class="property">Elite Gourmet</p>
                            </li>
                            <li>
                                <p class="name-feature">ASIN</p>
                                <p class="property">B09H3LWKYQ</p>
                            </li>
                            <li>
                                <p class="name-feature">Country of Origin</p>
                                <p class="property">China</p>
                            </li>

                            <li>
                                <p class="name-feature">Item model number</p>
                                <p class="property">EKT1001B</p>
                            </li>
                            <li>
                                <p class="name-feature">Customer Reviews</p>
                                <div class="w-100 star-review flex-wrap">
                                    <ul class="list-star">
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star"></i>
                                        </li>
                                        <li>
                                            <i class="icon-star text-main-4"></i>
                                        </li>
                                    </ul>
                                    <p class="caption text-main-2">Reviews (1.738)</p>
                                </div>
                            </li>
                            <li>
                                <p class="name-feature">Date First Available</p>
                                <p class="property"> September 24, 2021</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flat-title-tab-product-des">
                    <h5 class=" fw-semibold">
                        Reviews
                    </h5>
                    <div class="tab-main tab-review style-2">
                        <div class="tab-rating-wrap">
                            <div class="li rating-percent flex-shrink-0">
                                <p class="rate-percent">4.8 <span>/ 5</span></p>
                                <ul class="list-star justify-content-center">
                                    <li>
                                        <i class="icon-star"></i>
                                    </li>
                                    <li>
                                        <i class="icon-star"></i>
                                    </li>
                                    <li>
                                        <i class="icon-star"></i>
                                    </li>
                                    <li>
                                        <i class="icon-star"></i>
                                    </li>
                                    <li>
                                        <i class="icon-star text-main-4"></i>
                                    </li>
                                </ul>
                                <p class="text-cl-3">
                                    Based on 1.738 reviews
                                </p>
                            </div>
                            <span class="br-line d-none d-xl-block type-vertical"></span>
                            <ul class="li rating-progress-list flex-shrink-0">
                                <li>
                                    <p class="start-number body-text-3">5<i class="icon-star text-third"></i>
                                    </p>

                                    <div class="rating-progress">
                                        <div class="progress style-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                    <p class="count-review body-text-3">100</p>
                                </li>
                                <li>
                                    <p class="start-number body-text-3">4<i class="icon-star text-third"></i>
                                    </p>
                                    <div class="rating-progress">
                                        <div class="progress style-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 80%;"></div>
                                        </div>
                                    </div>
                                    <p class="count-review body-text-3">87</p>
                                </li>
                                <li>
                                    <p class="start-number body-text-3">3<i class="icon-star text-third"></i>
                                    </p>
                                    <div class="rating-progress">
                                        <div class="progress style-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 60%;"></div>
                                        </div>
                                    </div>
                                    <p class="count-review body-text-3">32</p>
                                </li>
                                <li>
                                    <p class="start-number body-text-3">2<i class="icon-star text-third"></i>
                                    </p>
                                    <div class="rating-progress">
                                        <div class="progress style-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 40%;"></div>
                                        </div>
                                    </div>
                                    <p class="count-review body-text-3">24</p>
                                </li>
                                <li>
                                    <p class="start-number body-text-3">1<i class="icon-star text-third"></i>
                                    </p>
                                    <div class="rating-progress">
                                        <div class="progress style-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <p class="count-review body-text-3">0</p>
                                </li>
                            </ul>
                            <span class="br-line d-none d-xl-block type-vertical"></span>
                            <div class="li rating-filter-wrap">
                                <p class="title-sidebar fw-bold">Filter by</p>
                                <ul class="rating-filter-list">
                                    <li><a href="#" class="active">All</a></li>
                                    <li><a href="#">5 sao (8)</a></li>
                                    <li><a href="#">4 sao (12)</a></li>
                                    <li><a href="#">3 sao (23)</a></li>
                                    <li><a href="#">2 sao (10)</a></li>
                                    <li><a href="#">1 sao (0)</a></li>
                                </ul>
                            </div>

                        </div>
                        <div class="tab-review-wrap">
                            <ul class="review-list">
                                <li class="box-review">
                                    <div class="avt">
                                        <img src="{{asset($filePath)}}/images/avatar/review-1.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <div class="author-wrap">
                                            <h6 class="name fw-semibold">
                                                <a href="#" class="link">Cameron Williamson</a>
                                            </h6>
                                            <ul class="verified">
                                                <li class="body-small">Color: Black</li>
                                                <li class="body-small fw-semibold text-main-2">
                                                    Verified Purchase
                                                </li>
                                            </ul>
                                            <ul class="list-star">
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star text-main-4"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <p class="text-review">
                                            Bought this nice little electric hot water kettle for an overnight
                                            date. She enjoyed tea and the hotel did not offer tea in the room.
                                            Problem solved! This kettle did its job, through the evening and
                                            into the morning we enjoyed many cups of nice, loose leaf tea. Too
                                            bad she ended up not liking me and eventually ghosted me. But, the
                                            tea was great thanks to this electric kettle. Highly recommend!
                                        </p>
                                        <p class="date-review body-small">
                                            14/12/2020 lúc 17:20
                                        </p>
                                    </div>
                                </li>
                                <li class="box-review">
                                    <div class="avt">
                                        <img src="{{asset($filePath)}}/images/avatar/review-5.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <div class="author-wrap">
                                            <h6 class="name fw-semibold">
                                                <a href="#" class="link">Cameron Williamson</a>
                                            </h6>
                                            <ul class="verified">
                                                <li class="body-small">Color: Black</li>
                                                <li class="body-small fw-semibold text-main-2">
                                                    Verified Purchase
                                                </li>
                                            </ul>
                                            <ul class="list-star">
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star text-main-4"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <p class="text-review">
                                            Nullam ornare a magna quis aliquet. Duis suscipit eros in suscipit
                                            venenatis. Pellentesque quis efficitur leo. Maecenas accumsan est in
                                            nibh interdum, quis dignissim neque scelerisque. Ut suscipit et leo
                                            sit amet lacinia. Sed a laoreet leo, ut tristique risus. Integer a
                                            est ut est semper fermentum nec quis nunc. Phasellus aliquam neque
                                            eget quam gravida, quis venenatis turpis tristique. Mauris id congue
                                            augue. Pellentesque hendrerit porttitor purus, vel porttitor sem
                                            blandit vel.
                                        </p>
                                        <p class="date-review body-small">
                                            14/12/2020 lúc 17:20
                                        </p>
                                    </div>
                                </li>
                                <li class="box-review">
                                    <div class="avt">
                                        <img src="{{asset($filePath)}}/images/avatar/review-6.jpg" alt="">
                                    </div>
                                    <div class="review-content">
                                        <div class="author-wrap">
                                            <h6 class="name fw-semibold">
                                                <a href="#" class="link">Cameron Williamson</a>
                                            </h6>
                                            <ul class="verified">
                                                <li class="body-small">Color: Black</li>
                                                <li class="body-small fw-semibold text-main-2">
                                                    Verified Purchase
                                                </li>
                                            </ul>
                                            <ul class="list-star">
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star text-main-4"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <p class="text-review">
                                            Suspendisse efficitur velit quis sodales facilisis. Aenean id enim
                                            nec purus interdum semper. In hac habitasse platea dictumst. Nulla
                                            posuere ac ligula sit amet posuere. Curabitur ultricies non dui ut
                                            blandit. In quis nulla nec tellus rutrum porttitor. Sed pharetra
                                            magna diam, et lacinia tortor congue ut.
                                        </p>
                                        <p class="date-review body-small">
                                            14/12/2020 lúc 17:20
                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="add-comment-wrap sticky-top w-100">
                                <h5 class="fw-semibold">Add your comment</h5>
                                <div>
                                    <form class="form-add-comment">
                                        <fieldset class="rate">
                                            <label>Rating:</label>
                                            <ul class="list-star justify-content-start">
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star"></i>
                                                </li>
                                                <li>
                                                    <i class="icon-star text-main-4"></i>
                                                </li>
                                            </ul>
                                        </fieldset>
                                        <fieldset>
                                            <label>Name:</label>
                                            <input type="text" placeholder="Your name" required="">
                                        </fieldset>
                                        <fieldset>
                                            <label>Email:</label>
                                            <input type="text" placeholder="Your email" required="">
                                        </fieldset>
                                        <fieldset class="align-items-sm-start">
                                            <label>Comment:</label>
                                            <textarea placeholder="Message"></textarea>
                                        </fieldset>
                                        <div class="btn-submit">
                                            <button type="submit" class="tf-btn btn-gray btn-large-2">
                                                <span class="text-white">Add Review</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Product Description Tab -->


    @push('js')
        <script type="module" src="{{asset($filePath)}}/js/drift.min.js"></script>
        <script type="module" src="{{asset($filePath)}}/js/zoom.js"></script>
        <script>
            $(document).ready(function() {

            });
        </script>
    @endpush
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
