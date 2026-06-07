<x-frontend-layout title="Compare" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <div class="block-header block-header--has-breadcrumb block-header--has-title">
        <div class="container">
            <div class="block-header__body">
                <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb__list">
                        @foreach($breadcrumb_list as $breadcrumb)
                            <li class="breadcrumb__item @if($loop->first) breadcrumb__item--parent breadcrumb__item--first @endif @if($loop->last) breadcrumb__item--current @endif">
                                @if(!$loop->last)
                                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                @else
                                    <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
                <h1 class="block-header__title">Compare</h1>
            </div>
        </div>
    </div>
    
    <div class="block">
        <div class="container">
            @php
                $compare = \Cart::session((Auth::id() ?? session()->getId()) . '_compare')->getContent();
            @endphp
            @if($compare->count() > 0)
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="compare-table">
                    <tbody>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Product</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">
                                <a href="{{ $item->attributes->url }}" class="compare-table__product-link">
                                    <div class="compare-table__product-image">
                                        <img src="{{ $item->attributes->image }}" alt="" width="200" height="200">
                                    </div>
                                    <div class="compare-table__product-name">{{ $item->name }}</div>
                                </a>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Availability</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">
                                <span class="badge badge-success">{{ $item->attributes->stock }}</span>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Price</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">${{ number_format($item->price, 2) }}</td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Add to cart</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">
                                <button type="button" class="btn btn-primary btn-sm btn-cart" 
                                    data-id="{{ $item->id }}" 
                                    data-name="{{ $item->name }}" 
                                    data-price="{{ $item->price }}" 
                                    data-image="{{ $item->attributes->image }}" 
                                    data-url="{{ $item->attributes->url }}">Add to cart</button>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">SKU</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">{{ $item->attributes->sku }}</td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Brand</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">{{ $item->attributes->brand }}</td>
                            @endforeach
                        </tr>
                        <tr class="compare-table__row">
                            <th class="compare-table__column compare-table__column--header">Remove</th>
                            @foreach($compare as $item)
                            <td class="compare-table__column compare-table__column--product">
                                <form action="{{ route('compare.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm">Remove</button>
                                </form>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center pb-5 pt-5">
                <h3 class="mb-4">Your compare list is empty</h3>
                <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
