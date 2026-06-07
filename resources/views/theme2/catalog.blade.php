<x-frontend-layout title="Home Page" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- site__body -->
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb block-header--has-title">
            <div class="container">
                <div class="block-header__body">
                    <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb__list">
                            @foreach($breadcrumb_list as $index => $item)
                            <li class="breadcrumb__item @if($loop->first) breadcrumb__item--parent breadcrumb__item--first @endif @if($loop->last) breadcrumb__item--current @endif">
                                @if(!$loop->last)
                                    <a href="{{ $item['url'] }}" class="breadcrumb__item-link">{{ $item['name'] }}</a>
                                @else
                                    <span class="breadcrumb__item-link">{{ $item['name'] }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ol>
                    </nav>
                    <h1 class="block-header__title">{{ isset($category) ? $category->name : 'Catalog' }}</h1>
                </div>
            </div>
        </div>
        <div class="block block-split">
            <div class="container">
                <div class="block-split__row row no-gutters">
                    <div class="block-split__item block-split__item-content col-auto">
                        <div class="block">
                            <div class="categories-list categories-list--layout--columns-4-full">
                                <ul class="categories-list__body">
                                    @foreach($categories as $category_item)
                                    <li class="categories-list__item">
                                        <a href="{{ route('catalog.show', $category_item->slug) }}">
                                            <div class="image image--type--category">
                                                <div class="image__body">
                                                    <img class="image__tag"
                                                        src="{{ $category_item->image ? asset($category_item->image) : asset('frontend/images/no-image.jpg') }}"
                                                        alt="{{ $category_item->name }}">
                                                </div>
                                            </div>
                                            <div class="categories-list__item-name">{{ $category_item->name }}</div>
                                        </a>
                                        <div class="categories-list__item-products">{{ $category_item->product_count }} Products</div>
                                    </li>
                                    <li class="categories-list__divider"></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-space block-space--layout--before-footer"></div>
            </div>
        </div>
    </div>
    <!-- site__body / end -->
</x-frontend-layout>
