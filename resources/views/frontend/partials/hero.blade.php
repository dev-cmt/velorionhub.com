
@php
    $featuredProduct = ($special_offers ?? collect())->first() ?? ($hot_deals ?? collect())->first();
    $bannerSideProducts = ($hot_deals ?? collect())->when($featuredProduct, fn($c) => $c->where('id', '!=', $featuredProduct->id))->take(2);

    $productUrl  = null;
    $mainImage   = null;
    $categoryName = null;
    if ($featuredProduct) {
        $productUrl   = route('product.show', $featuredProduct->slug);
        $mainImage    = $featuredProduct->main_image ? asset($featuredProduct->main_image) : asset('images/no-image.jpg');
        $categoryName = $featuredProduct->category->name ?? 'Uncategorized';
    }
@endphp
    <!-- Banner Product -->
    <section class="has-bg-img" data-bg-img="{{ asset('frontend') }}/images/banner/banner-6.jpg">
        <div class="container">
            <div class="banner-product flex-xl-nowrap justify-content-center">
                @if($featuredProduct)
                    <div class="product-wrap hover-img flex-md-nowrap justify-content-center">
                        <a href="{{ $productUrl }}" class="d-inline-flex item-product img-style">
                            <img src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $featuredProduct->name }}" class="lazyload">
                        </a>
                        <div class="info-product text-center text-md-start">
                            <div class="box-title">
                                <p class="tag-new text-white text-uppercase title-sidebar">New arrival</p>
                                <h1 class="name">
                                    <a href="{{ $productUrl }}" class="text-white text-uppercase link">
                                        {{ $categoryName }}
                                    </a>
                                </h1>
                            </div>
                            <div class="box-price">
                                <p class="start text-white">Starting</p>
                                <h1 class="price text-primary">TK {{ number_format($featuredProduct->sale_price, 2) }}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="other-item flex-xl-column flex-md-row">
                    @foreach($bannerSideProducts as $product)
                        @include('frontend.partials.product-item-horizontal', ['product' => $product, 'bgWhite' => true])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Banner Product -->
