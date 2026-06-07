<x-frontend-layout title="Wishlist" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                <h1 class="block-header__title">Wishlist</h1>
            </div>
        </div>
    </div>
    
    <div class="block">
        <div class="container">
            @php
                $wishlist = \Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->getContent();
            @endphp
            @if($wishlist->count() > 0)
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table class="wishlist">
                <thead class="wishlist__head">
                    <tr class="wishlist__row">
                        <th class="wishlist__column wishlist__column--image">Image</th>
                        <th class="wishlist__column wishlist__column--product">Product</th>
                        <th class="wishlist__column wishlist__column--stock">Stock Status</th>
                        <th class="wishlist__column wishlist__column--price">Price</th>
                        <th class="wishlist__column wishlist__column--tocart"></th>
                        <th class="wishlist__column wishlist__column--remove"></th>
                    </tr>
                </thead>
                <tbody class="wishlist__body">
                    @foreach($wishlist as $item)
                    <tr class="wishlist__row">
                        <td class="wishlist__column wishlist__column--image">
                            <a href="{{ $item->attributes->url }}"><img src="{{ $item->attributes->image }}" alt=""></a>
                        </td>
                        <td class="wishlist__column wishlist__column--product">
                            <a href="{{ $item->attributes->url }}" class="wishlist__product-name">{{ $item->name }}</a>
                        </td>
                        <td class="wishlist__column wishlist__column--stock">
                            <div class="badge badge-success">{{ $item->attributes->stock }}</div>
                        </td>
                        <td class="wishlist__column wishlist__column--price">${{ number_format($item->price, 2) }}</td>
                        <td class="wishlist__column wishlist__column--tocart">
                            <button type="button" class="btn btn-primary btn-sm btn-cart" 
                                data-id="{{ $item->id }}" 
                                data-name="{{ $item->name }}" 
                                data-price="{{ $item->price }}" 
                                data-image="{{ $item->attributes->image }}" 
                                data-url="{{ $item->attributes->url }}">Add to cart</button>
                        </td>
                        <td class="wishlist__column wishlist__column--remove">
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm btn-svg-icon">
                                    <svg width="12px" height="12px"><use xlink:href="{{asset('frontend')}}/images/sprite.svg#cross-12"></use></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center pb-5 pt-5">
                <h3 class="mb-4">Your wishlist is empty</h3>
                <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
