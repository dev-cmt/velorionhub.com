<x-frontend-layout :title="'Shop'" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- site__body -->
        <div class="site__body">
            <div class="block-header block-header--has-breadcrumb block-header--has-title">
                <div class="container">
                    <div class="block-header__body">
                        <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                            <ol class="breadcrumb__list">
                                @foreach($breadcrumb_list as $breadcrumb)
                                    @if($loop->last)
                                        <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">
                                            <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                        </li>
                                    @else
                                        <li class="breadcrumb__item breadcrumb__item--parent {{ $loop->first ? 'breadcrumb__item--first' : '' }}">
                                            <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                        <h1 class="block-header__title">{{ request('category') ? $categories->where('slug', request('category'))->first()->name ?? 'Shop' : 'Shop' }}</h1>
                    </div>
                </div>
            </div>
            <div class="block block-split">
                <div class="container">
                    <div class="block-split block-split--has-sidebar">
                        <div class="container">
                            <div class="block-split__row row no-gutters">
                                <div class="block-split__item block-split__item-sidebar col-auto">
                                    <div class="sidebar sidebar--offcanvas--mobile">
                                        <div class="sidebar__backdrop"></div>
                                        <div class="sidebar__body">
                                            <div class="sidebar__header">
                                                <div class="sidebar__title">Filters</div>
                                                <button class="sidebar__close" type="button"><svg width="12" height="12">
                                                        <path d="M10.8,10.8L10.8,10.8c-0.4,0.4-1,0.4-1.4,0L6,7.4l-3.4,3.4c-0.4,0.4-1,0.4-1.4,0l0,0c-0.4-0.4-0.4-1,0-1.4L4.6,6L1.2,2.6 c-0.4-0.4-0.4-1,0-1.4l0,0c0.4-0.4,1-0.4,1.4,0L6,4.6l3.4-3.4c0.4-0.4,1-0.4,1.4,0l0,0c0.4,0.4,0.4,1,0,1.4L7.4,6l3.4,3.4 C11.2,9.8,11.2,10.4,10.8,10.8z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="sidebar__content">
                                                <form class="ajax-form">
                                                    <div class="widget widget-filters widget-filters--offcanvas--mobile"
                                                        data-collapse data-collapse-opened-class="filter--opened">
                                                        <div class="widget__header widget-filters__header">
                                                            <h4>Filters</h4>
                                                        </div>
                                                        <div class="widget-filters__list">
                                                            <div class="widget-filters__item">
                                                                <div class="filter filter--opened" data-collapse-item>
                                                                    <button type="button" class="filter__title"
                                                                        data-collapse-trigger>
                                                                        Categories
                                                                        <span class="filter__arrow"><svg width="12px" height="7px">
                                                                                <path d="M0.286,0.273 L0.286,0.273 C-0.070,0.629 -0.075,1.204 0.276,1.565 L5.516,6.993 L10.757,1.565 C11.108,1.204 11.103,0.629 10.747,0.273 L10.747,0.273 C10.385,-0.089 9.796,-0.086 9.437,0.279 L5.516,4.296 L1.596,0.279 C1.237,-0.086 0.648,-0.089 0.286,0.273 Z" />
                                                                            </svg></span>
                                                                    </button>
                                                                    <div class="filter__body" data-collapse-content>
                                                                        <div class="filter__container">
                                                                            <div class="filter-list">
                                                                                <div class="filter-list__list">
                                                                                    @foreach($categories as $category)
                                                                                        <label class="filter-list__item">
                                                                                            <span class="input-check filter-list__input">
                                                                                                <span class="input-check__body">
                                                                                                    <input class="input-check__input category-filter" type="checkbox" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                                                                                                    <span class="input-check__box"></span>
                                                                                                    <span class="input-check__icon">
                                                                                                        <svg width="9" height="7">
                                                                                                            <path d="M9,1.3L8.6,0.9c-0.2-0.2-0.5-0.2-0.7,0L3.1,5.7L1.1,3.7c-0.2-0.2-0.5-0.2-0.7,0L0,4.1c-0.2,0.2-0.2,0.5,0,0.7 l3.1,3.1c0.1,0.1,0.2,0.2,0.4,0.2s0.3-0.1,0.4-0.2l5.1-5.1C9.2,1.8,9.2,1.5,9,1.3z" />
                                                                                                        </svg>
                                                                                                    </span>
                                                                                                </span>
                                                                                            </span>
                                                                                            <span class="filter-list__title" style="font-weight: 600;">{{ $category->name }}</span>
                                                                                        </label>
                                                                                        @if($category->children->count() > 0)
                                                                                            <div style="margin-left: 25px;">
                                                                                                @foreach($category->children as $child)
                                                                                                    <label class="filter-list__item">
                                                                                                        <span class="input-check filter-list__input">
                                                                                                            <span class="input-check__body">
                                                                                                                <input class="input-check__input category-filter" type="checkbox" value="{{ $child->slug }}" {{ request('category') == $child->slug ? 'checked' : '' }}>
                                                                                                                <span class="input-check__box"></span>
                                                                                                                <span class="input-check__icon">
                                                                                                                    <svg width="9" height="7">
                                                                                                                        <path d="M9,1.3L8.6,0.9c-0.2-0.2-0.5-0.2-0.7,0L3.1,5.7L1.1,3.7c-0.2-0.2-0.5-0.2-0.7,0L0,4.1c-0.2,0.2-0.2,0.5,0,0.7 l3.1,3.1c0.1,0.1,0.2,0.2,0.4,0.2s0.3-0.1,0.4-0.2l5.1-5.1C9.2,1.8,9.2,1.5,9,1.3z" />
                                                                                                                    </svg>
                                                                                                                </span>
                                                                                                            </span>
                                                                                                        </span>
                                                                                                        <span class="filter-list__title">{{ $child->name }}</span>
                                                                                                    </label>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="widget-filters__item">
                                                                <div class="filter filter--opened" data-collapse-item>
                                                                    <button type="button" class="filter__title" data-collapse-trigger>
                                                                        Price
                                                                        <span class="filter__arrow">
                                                                            <svg width="12px" height="7px">
                                                                                <path d="M0.286,0.273 L0.286,0.273 C-0.070,0.629 -0.075,1.204 0.276,1.565 L5.516,6.993 L10.757,1.565 C11.108,1.204 11.103,0.629 10.747,0.273 L10.747,0.273 C10.385,-0.089 9.796,-0.086 9.437,0.279 L5.516,4.296 L1.596,0.279 C1.237,-0.086 0.648,-0.089 0.286,0.273 Z" />
                                                                            </svg>
                                                                        </span>
                                                                    </button>
                                                                    <div class="filter__body" data-collapse-content>
                                                                        <div class="filter__container">
                                                                            <form action="{{ route('shop') }}" method="GET" id="price-filter-form">
                                                                                @foreach(request()->except('min_price', 'max_price', 'page') as $key => $value)
                                                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                                                @endforeach
                                                                                <div class="form-row">
                                                                                    <div class="form-group col-6">
                                                                                        <label for="filter_price_from">From</label>
                                                                                        <input id="filter_price_from" class="form-control form-control-sm" type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min">
                                                                                    </div>
                                                                                    <div class="form-group col-6">
                                                                                        <label for="filter_price_to">To</label>
                                                                                        <input id="filter_price_to" class="form-control form-control-sm" type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max">
                                                                                    </div>
                                                                                </div>
                                                                                <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">Filter</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="widget-filters__item">
                                                                <div class="filter filter--opened" data-collapse-item>
                                                                    <button type="button" class="filter__title" data-collapse-trigger>
                                                                        Brand
                                                                        <span class="filter__arrow">
                                                                            <svg width="12px" height="7px">
                                                                                <path d="M0.286,0.273 L0.286,0.273 C-0.070,0.629 -0.075,1.204 0.276,1.565 L5.516,6.993 L10.757,1.565 C11.108,1.204 11.103,0.629 10.747,0.273 L10.747,0.273 C10.385,-0.089 9.796,-0.086 9.437,0.279 L5.516,4.296 L1.596,0.279 C1.237,-0.086 0.648,-0.089 0.286,0.273 Z" />
                                                                            </svg>
                                                                        </span>
                                                                    </button>
                                                                    <div class="filter__body" data-collapse-content>
                                                                        <div class="filter__container">
                                                                            <div class="filter-list">
                                                                                <div class="filter-list__list">
                                                                                    @foreach($brands as $brand)
                                                                                        <label class="filter-list__item">
                                                                                            <span class="input-check filter-list__input">
                                                                                                <span class="input-check__body">
                                                                                                    <input class="input-check__input brand-filter" type="checkbox" value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'checked' : '' }} onchange="window.location.href = '{{ route('shop', array_merge(request()->except('brand', 'page'), ['brand' => request('brand') == $brand->slug ? '' : $brand->slug])) }}'">
                                                                                                    <span class="input-check__box"></span>
                                                                                                    <span class="input-check__icon"><svg width="9" height="7">
                                                                                                            <path d="M9,1.3L8.6,0.9c-0.2-0.2-0.5-0.2-0.7,0L3.1,5.7L1.1,3.7c-0.2-0.2-0.5-0.2-0.7,0L0,4.1c-0.2,0.2-0.2,0.5,0,0.7 l3.1,3.1c0.1,0.1,0.2,0.2,0.4,0.2s0.3-0.1,0.4-0.2l5.1-5.1C9.2,1.8,9.2,1.5,9,1.3z" />
                                                                                                        </svg></span>
                                                                                                </span>
                                                                                            </span>
                                                                                            <span class="filter-list__title">{{ $brand->name }}</span>
                                                                                        </label>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="widget-filters__actions d-flex">
                                                            <button
                                                                class="btn btn-primary btn-sm ajax-start">Filter</button>
                                                            <button class="btn btn-secondary btn-sm ajax-reset"
                                                                onclick='window.location.href="fuel-system-and-filters.html"'>Reset</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="card widget widget-products d-none d-lg-block">
                                                    <div class="widget__header">
                                                        <h4>Latest Products</h4>
                                                    </div>
                                                    <div class="widget-products__list">
                                                        @foreach($latest_products as $l_product)
                                                            <div class="widget-products__item">
                                                                <div class="widget-products__image image image--type--product">
                                                                    <a href="{{ route('product.show', $l_product->slug) }}" class="image__body">
                                                                        <img class="image__tag" src="{{ $l_product->main_image ? asset($l_product->main_image) : asset('images/no-image.jpg') }}" alt="{{ $l_product->name }}">
                                                                    </a>
                                                                </div>
                                                                <div class="widget-products__info">
                                                                    <div class="widget-products__name">
                                                                        <a href="{{ route('product.show', $l_product->slug) }}">{{ $l_product->name }}</a>
                                                                    </div>
                                                                    <div class="widget-products__prices">
                                                                        @if($l_product->sale_price < $l_product->regular_price)
                                                                            <div class="product-card__price product-card__price--new">TK {{ number_format($l_product->sale_price, 2) }}</div>
                                                                        @else
                                                                            <div class="product-card__price product-card__price--current">TK {{ number_format($l_product->regular_price, 2) }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-split__item block-split__item-content col-auto">
                                    <div class="block">
                                        <div class="products-view">
                                            <div
                                                class="products-view__options view-options view-options--offcanvas--mobile">
                                                <div class="view-options__body">
                                                    <button type="button"
                                                        class="view-options__filters-button filters-button">
                                                        <span class="filters-button__icon"><svg width="16" height="16">
                                                                <path d="M7,14v-2h9v2H7z M14,7h2v2h-2V7z M12.5,6C12.8,6,13,6.2,13,6.5v3c0,0.3-0.2,0.5-0.5,0.5h-2
	C10.2,10,10,9.8,10,9.5v-3C10,6.2,10.2,6,10.5,6H12.5z M7,2h9v2H7V2z M5.5,5h-2C3.2,5,3,4.8,3,4.5v-3C3,1.2,3.2,1,3.5,1h2
	C5.8,1,6,1.2,6,1.5v3C6,4.8,5.8,5,5.5,5z M0,2h2v2H0V2z M9,9H0V7h9V9z M2,14H0v-2h2V14z M3.5,11h2C5.8,11,6,11.2,6,11.5v3
	C6,14.8,5.8,15,5.5,15h-2C3.2,15,3,14.8,3,14.5v-3C3,11.2,3.2,11,3.5,11z" />
                                                            </svg>
                                                        </span>
                                                        <span class="filters-button__title">Filters</span>
                                                    </button>
                                                    <div class="view-options__layout layout-switcher">
                                                        <div class="layout-switcher__list">
                                                            <button type="button"
                                                                class="layout-switcher__button layout-switcher__button--active"
                                                                data-layout="grid" data-with-features="false">
                                                                <svg width="16" height="16">
                                                                    <path d="M15.2,16H9.8C9.4,16,9,15.6,9,15.2V9.8C9,9.4,9.4,9,9.8,9h5.4C15.6,9,16,9.4,16,9.8v5.4C16,15.6,15.6,16,15.2,16z M15.2,7
	H9.8C9.4,7,9,6.6,9,6.2V0.8C9,0.4,9.4,0,9.8,0h5.4C15.6,0,16,0.4,16,0.8v5.4C16,6.6,15.6,7,15.2,7z M6.2,16H0.8
	C0.4,16,0,15.6,0,15.2V9.8C0,9.4,0.4,9,0.8,9h5.4C6.6,9,7,9.4,7,9.8v5.4C7,15.6,6.6,16,6.2,16z M6.2,7H0.8C0.4,7,0,6.6,0,6.2V0.8
	C0,0.4,0.4,0,0.8,0h5.4C6.6,0,7,0.4,7,0.8v5.4C7,6.6,6.6,7,6.2,7z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button" class="layout-switcher__button"
                                                                data-layout="table" data-with-features="false">
                                                                <svg width="16" height="16">
                                                                    <path d="M15.2,16H0.8C0.4,16,0,15.6,0,15.2v-2.4C0,12.4,0.4,12,0.8,12h14.4c0.4,0,0.8,0.4,0.8,0.8v2.4C16,15.6,15.6,16,15.2,16z
	 M15.2,10H0.8C0.4,10,0,9.6,0,9.2V6.8C0,6.4,0.4,6,0.8,6h14.4C15.6,6,16,6.4,16,6.8v2.4C16,9.6,15.6,10,15.2,10z M15.2,4H0.8
	C0.4,4,0,3.6,0,3.2V0.8C0,0.4,0.4,0,0.8,0h14.4C15.6,0,16,0.4,16,0.8v2.4C16,3.6,15.6,4,15.2,4z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="view-options__spring"></div>
                                                    <form class="catalog_sort_block">
                                                        <div class="_group_values">
                                                            <div class="_value">
                                                                Sort By:
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <form class="catalog_sort_block" id="catalog_sort_block">
                                                        <div class="_group_values" data-sort_name="pagetitle">
                                                            <div class="_value _active_" data-srot_dir="ASC">
                                                                Name <i class="fas fa-sort-alpha-up"></i>
                                                            </div>
                                                            <div class="_value" data-srot_dir="DESC">
                                                                Name <i class="fas fa-sort-alpha-down"></i>
                                                            </div>
                                                            <input type="hidden" name="pagetitle" value="ASC">
                                                        </div>
                                                    </form>
                                                    <form class="catalog_sort_block" id="catalog_sort_block_price">
                                                        <div class="_group_values" data-sort_name="Data.price">
                                                            <div class="_value _active_" data-srot_dir="ASC">
                                                                Price <i class="fas fa-sort-numeric-up"></i>
                                                            </div>
                                                            <div class="_value" data-srot_dir="DESC">
                                                                Price <i class="fas fa-sort-numeric-down"></i>
                                                            </div>
                                                            <input type="hidden" name="Data.price" value="ASC">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="products-view__list products-list products-list--grid--4"
                                                data-layout="grid" data-with-features="false">
                                                <div class="products-list__head">
                                                    <div class="products-list__column products-list__column--image">
                                                        Image</div>
                                                    <div class="products-list__column products-list__column--meta">SKU
                                                    </div>
                                                    <div class="products-list__column products-list__column--product">
                                                        Product</div>
                                                    <div class="products-list__column products-list__column--rating">
                                                        Rating</div>
                                                    <div class="products-list__column products-list__column--price">
                                                        Price</div>
                                                </div>
                                                <div class="products-list__content ajax-container">
                                                    @foreach($products as $product)
                                                        <div class="products-list__item ajax-item">
                                                            @include('frontend.partials.product-item', ['product' => $product])
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="products-view__pagination">
                                                {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- site__body / end -->
    </div>
    <!-- site / end -->

    @push('js')
    <script>
        $(function() {
            // Price Filter
            const priceSlider = document.getElementById('price-slider');
            if (priceSlider) {
                noUiSlider.create(priceSlider, {
                    start: [{{ request('min_price', 0) }}, {{ request('max_price', 10000) }}],
                    connect: true,
                    range: {
                        'min': 0,
                        'max': 10000
                    },
                    step: 10
                });

                priceSlider.noUiSlider.on('update', function(values, handle) {
                    $('#filter-price-from').text(Math.round(values[0]));
                    $('#filter-price-to').text(Math.round(values[1]));
                    $('#min_price').val(Math.round(values[0]));
                    $('#max_price').val(Math.round(values[1]));
                });
            }

            $('#apply-price-filter').on('click', function() {
                const min = $('#min_price').val();
                const max = $('#max_price').val();
                let url = new URL(window.location.href);
                url.searchParams.set('min_price', min);
                url.searchParams.set('max_price', max);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });

            // Category Filter
            $('.category-filter').on('change', function() {
                let url = new URL(window.location.href);
                if (this.checked) {
                    url.searchParams.set('category', this.value);
                } else {
                    url.searchParams.delete('category');
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });

            // Brand Filter
            $('.brand-filter').on('change', function() {
                let url = new URL(window.location.href);
                if (this.checked) {
                    url.searchParams.set('brand', this.value);
                } else {
                    url.searchParams.delete('brand');
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
            
        });
    </script>
    @endpush
</x-frontend-layout>
