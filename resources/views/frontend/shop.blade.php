<x-frontend-layout :title="'Shop'" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    @push('css')
        <link rel="stylesheet" href="{{asset($filePath)}}/css/nice-select.css">
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
                    <span class="body-small">Product Grid</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- Main Content -->
    <div class="flat-content">
        <div class="container">
            <div class="tf-product-view-content wrapper-control-shop">
                <div class="canvas-filter-product sidebar-filter handle-canvas left">
                    <div class="canvas-wrapper">
                        <div class="canvas-header d-flex d-xl-none">
                            <h5 class="title">Filter</h5>
                            <span class="icon-close link icon-close-popup close-filter"
                                data-bs-dismiss="offcanvas"></span>
                        </div>
                        <div class="canvas-body">
                            <div class="facet-categories">
                                <h6 class="title fw-medium">Show all categories</h6>
                                <ul>
                                    @foreach($categories as $cat)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $cat->slug]) }}">{{ $cat->name }} <i class="icon-arrow-right"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="widget-facet facet-fieldset has-loadmore">
                                <p class="facet-title title-sidebar fw-semibold">Brand</p>
                                <div class="box-fieldset-item">
                                    @foreach($brands as $brand)
                                        <fieldset class="fieldset-item">
                                            <input type="checkbox" name="brand" class="tf-check filter-item" data-filter="{{ $brand->slug }}" id="{{ $brand->slug }}">
                                            <label for="{{ $brand->slug }}">{{ $brand->name }}</label>
                                        </fieldset>
                                    @endforeach
                                </div>
                                <div class="btn-loadmore">See more <i class="icon-arrow-down"></i></div>
                            </div>
                            <div class="widget-facet facet-price">
                                <p class="facet-title title-sidebar fw-semibold">Price</p>
                                <div class="box-fieldset-item">
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="price" class="tf-check" id="u10">
                                        <label for="u10">Under TK 10</label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="price" class="tf-check" id="u15">
                                        <label for="u15">TK 10 to TK 15</label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="price" class="tf-check" id="u25">
                                        <label for="u25">TK 15 to TK 25</label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="price" class="tf-check" id="up35">
                                        <label for="up35">TK 35 & Above</label>
                                    </fieldset>
                                </div>
                                <div class="box-price-product">
                                    <form class="w-100 form-filter-price">
                                        <div class="cols w-100">
                                            <fieldset class="box-price-item">
                                                <input type="number" class="min-price price-input" name="price"
                                                    placeholder="TK Min">
                                            </fieldset>
                                            <span class="br-line"></span>
                                            <fieldset class="box-price-item">
                                                <input type="number" class="max-price price-input" name="price"
                                                    placeholder="TK Max">
                                            </fieldset>
                                        </div>
                                        <div class="btn-filter-price cs-pointer link">
                                            <span class="title-sidebar fw-bold">
                                                Go
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="widget-facet facet-vote">
                                <p class="facet-title title-sidebar fw-semibold">Customer Review</p>
                                <div class="box-fieldset-item">
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="starRate" class="tf-check" id="fiveStar">
                                        <label for="fiveStar">
                                            <span class="list-star">
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                            </span>
                                        </label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="starRate" class="tf-check" id="fourStar">
                                        <label for="fourStar">
                                            <span class="list-star">
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star text-main-4"></i>
                                            </span>
                                            <span class="body-text-3">& Up</span>
                                        </label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="starRate" class="tf-check" id="threeStar">
                                        <label for="threeStar">
                                            <span class="list-star">
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                            </span>
                                            <span class="body-text-3">& Up</span>
                                        </label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="starRate" class="tf-check" id="twoStar">
                                        <label for="twoStar">
                                            <span class="list-star">
                                                <i class="icon-star"></i>
                                                <i class="icon-star"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                            </span>
                                            <span class="body-text-3">& Up</span>
                                        </label>
                                    </fieldset>
                                    <fieldset class="fieldset-item">
                                        <input type="radio" name="starRate" class="tf-check" id="oneStar">
                                        <label for="oneStar">
                                            <span class="list-star">
                                                <i class="icon-star"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                                <i class="icon-star text-main-4"></i>
                                            </span>
                                            <span class="body-text-3">& Up</span>
                                        </label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="canvas-bottom d-flex d-xl-none">
                            <button id="reset-filter" class="tf-btn btn-reset w-100">
                                <span class="caption text-white">Reset Filters</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="content-area">
                    <div class="tf-shop-control flex-wrap gap-10">
                        <div class="d-flex align-items-center gap-10">
                            <button id="filterShop" class="tf-btn-filter d-flex d-xl-none">
                                <span class="icon icon-filter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#121212"
                                        viewBox="0 0 256 256">
                                        <path
                                            d="M176,80a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H184A8,8,0,0,1,176,80ZM40,88H144v16a8,8,0,0,0,16,0V56a8,8,0,0,0-16,0V72H40a8,8,0,0,0,0,16Zm176,80H120a8,8,0,0,0,0,16h96a8,8,0,0,0,0-16ZM88,144a8,8,0,0,0-8,8v16H40a8,8,0,0,0,0,16H80v16a8,8,0,0,0,16,0V152A8,8,0,0,0,88,144Z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="body-md-2 fw-medium">Filter</span>
                            </button>
                            @if($products->total() > 0)
                                <p class="body-text-3 d-none d-lg-block">
                                    {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results
                                    @if(request()->filled('search'))
                                        for "<span class="title-sidebar fw-bold">{{ request()->get('search') }}</span>"
                                    @endif
                                </p>
                            @else
                                <p class="body-text-3 d-none d-lg-block">
                                    0 results
                                    @if(request()->filled('search'))
                                        for "<span class="title-sidebar fw-bold">{{ request()->get('search') }}</span>"
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="tf-control-view flat-title-tab-product flex-wrap">
                            <ul class="tf-control-layout menu-tab-line" role="tablist">
                                <li class="tf-view-layout-switch" data-tab="tabgrid-1">
                                    <a href="#" class="tab-link main-title link fw-semibold d-flex active"
                                        data-bs-toggle="tab">
                                        <i class="icon-menu-dots"></i>
                                    </a>
                                </li>
                                <li class="tf-view-layout-switch" data-tab="tabgrid-2">
                                    <a href="#" class="tab-link main-title link d-flex fw-semibold"
                                        data-bs-toggle="tab">
                                        <i class="icon-dot-line"></i>
                                    </a>
                                </li>
                                <li class="tf-view-layout-switch" data-tab="tablist-1">
                                    <a href="#" class="tab-link main-title link d-flex fw-semibold"
                                        data-bs-toggle="tab">
                                        <i class="icon-list-1"></i>
                                    </a>
                                </li>
                                <li class="tf-view-layout-switch" data-tab="tablist-2">
                                    <a href="#" class="tab-link main-title link d-flex fw-semibold"
                                        data-bs-toggle="tab">
                                        <i class="icon-list-2"></i>
                                    </a>
                                </li>
                            </ul>
                            @php
                                $currentLimit = request()->get('limit', 20);
                            @endphp
                            <div class="tf-my-dropdown tf-control-show nice-select" tabindex="0">
                                <div class="btn-select">
                                    <i class="icon-menu-dots"></i>
                                    <p class="body-text-3 w-100 current">Show: {{ $currentLimit }}</p>
                                    <i class="icon-arrow-down fs-10"></i>
                                </div>
                                <ul class="list">
                                    <li class="option select-item{{ $currentLimit == 5 ? ' selected' : '' }}" data-sort-value="0-5">
                                        <span class="text-value-item">Show: 5</span>
                                    </li>
                                    <li class="option select-item{{ $currentLimit == 10 ? ' selected' : '' }}" data-sort-value="0-10">
                                        <span class="text-value-item">Show: 10</span>
                                    </li>
                                    <li class="option select-item{{ $currentLimit == 15 ? ' selected' : '' }}" data-sort-value="0-15">
                                        <span class="text-value-item">Show: 15</span>
                                    </li>
                                    <li class="option select-item{{ $currentLimit == 20 ? ' selected' : '' }}" data-sort-value="0-20">
                                        <span class="text-value-item">Show: 20</span>
                                    </li>
                                    <li class="option select-item{{ $currentLimit == 50 ? ' selected' : '' }}" data-sort-value="0-50">
                                        <span class="text-value-item">Show: 50</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="tf-dropdown-sort tf-sort type-sort-by" data-bs-toggle="dropdown">
                                <div class="btn-select w-100">
                                    <i class="icon-sort"></i>
                                    <p class="body-text-3 w-100">Sort by: <span
                                            class="text-sort-value">Featured</span></p>
                                    <i class="icon-arrow-down fs-10"></i>
                                </div>
                                <div class="dropdown-menu">
                                    <div class="select-item" data-sort-value="best-selling">
                                        <span class="text-value-item">Featured</span>
                                    </div>
                                    <div class="select-item" data-sort-value="a-z">
                                        <span class="text-value-item">Alphabetically, A-Z</span>
                                    </div>
                                    <div class="select-item" data-sort-value="z-a">
                                        <span class="text-value-item">Alphabetically, Z-A</span>
                                    </div>
                                    <div class="select-item" data-sort-value="price-low-high">
                                        <span class="text-value-item">Price, low to high</span>
                                    </div>
                                    <div class="select-item" data-sort-value="price-high-low">
                                        <span class="text-value-item">Price, high to low</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="meta-filter-shop" style="display: none;">
                        <div id="product-count-grid" class="count-text"></div>
                        <div id="product-count-list" class="count-text"></div>
                        <div id="applied-filters"></div>
                        <button id="remove-all" class="remove-all-filters" style="display: none;">
                            <span class="caption">REMOVE ALL</span>
                            <i class="icon icon-close"></i>
                        </button>
                    </div>
                    <div class="gridLayout-wrapper">
                        <div class="tf-grid-layout lg-col-4 md-col-3 sm-col-2 flat-grid-product wrapper-shop layout-tabgrid-1"
                            id="gridLayout">
                            @foreach($products as $index => $product)
                                @include('frontend.partials.product-item-deal', [
                                    'product' => $product,
                                    'showDetail' => true,
                                    'showActionBtn' => true,
                                    'wowDelay' => ($index * 0.1) . 's'
                                ])
                            @endforeach

                            {!! $products->links('vendor.pagination.shop-paginator') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Content -->

    @push('js')
        <script src="{{asset($filePath)}}/js/jquery.nice-select.js"></script>
        <script src="{{asset($filePath)}}/js/shop.js"></script>
    @endpush
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
