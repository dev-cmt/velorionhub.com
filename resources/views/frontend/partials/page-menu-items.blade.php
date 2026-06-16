@php
    $menuPages = $pages ?? collect();
    $itemClass = $itemClass ?? '';
    $linkClass = $linkClass ?? '';
    $spanText = $spanText ?? false;
    $excludeSlugs = $excludeSlugs ?? [];

    $pageRouteMap = [
        'home' => 'home',
        'shop' => 'shop',
        'catalog' => 'catalog',
        'blog' => 'blog',
        'cart' => 'cart',
        'checkout' => 'checkout',
        'wishlist' => 'wishlist',
        'compare' => 'compare',
        'about-us' => 'about.us',
        'contacts' => 'contacts',
        'contact-us' => 'contacts',
        'faq' => 'faq',
        'track-order' => 'track.order',
        'privacy-policy' => 'privacy.policy',
        'return-policy' => 'return.policy',
        'terms-conditions' => 'terms.conditions',
        'my-account' => 'my.account',
    ];

    $excluded = collect($excludeSlugs)->filter()->values()->all();
@endphp

@foreach($menuPages->whereNotIn('slug', $excluded) as $page)
    @php
        $routeName = $pageRouteMap[$page->slug] ?? null;
        $pageUrl = ($routeName && Route::has($routeName)) ? route($routeName) : route('pages.show', $page->slug);
    @endphp
    <li @class([$itemClass => $itemClass])>
        <a href="{{ $pageUrl }}" @class([$linkClass => $linkClass])>
            @if($spanText)
                <span>{{ $page->title }}</span>
            @else
                {{ $page->title }}
            @endif
        </a>
    </li>
@endforeach
