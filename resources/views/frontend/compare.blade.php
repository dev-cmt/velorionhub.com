<x-frontend-layout title="Compare" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                    <span class="body-small"> Compare</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- Compare -->
    @php
        $compare = \Cart::session((Auth::id() ?? session()->getId()) . '_compare')->getContent();
    @endphp
    <div class="tf-sp-2">
        <div class="container">
            @if($compare->count() > 0)
            <div class="tf-compare">
                <table class="tf-table-compare">
                    <tbody>
                        <tr class="tf-compare-row row-info">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Product Name</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col tf-compare-info">
                                <div class="compare-item_info">
                                    <a href="{{ $item->attributes->url }}"
                                        class="text-line-clamp-2 body-md-2 fw-semibold text-secondary link">{{ $item->name }}</a>
                                    <span class="icon">
                                        <i class="icon-close remove link cs-pointer" data-id="{{ $item->id }}"></i>
                                    </span>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row row-image">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Image</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col tf-compare-image">
                                <a href="{{ $item->attributes->url }}" class="image">
                                    <img src="{{ $item->attributes->image }}"
                                        data-src="{{ $item->attributes->image }}" alt="{{ $item->name }}" class="lazyload">
                                </a>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">SKU</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col"> <span>{{ $item->attributes->sku }}</span></td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Price</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col">
                                <p class="price-wrap fw-medium flex-nowrap mb-0">
                                    <span class="new-price price-text fw-medium mb-0">TK {{ number_format($item->price, 2) }}</span>
                                </p>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Brand</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col"><span>{{ $item->attributes->brand }}</span></td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Category</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col"><span>{{ $item->attributes->category }}</span></td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Availability</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col"><span>{{ $item->attributes->stock }}</span></td>
                            @endforeach
                        </tr>
                        <tr class="tf-compare-row">
                            <td class="tf-compare-col">
                                <h6 class="fw-semibold">Add To Cart</h6>
                            </td>
                            @foreach($compare as $item)
                            <td class="tf-compare-col">
                                <button type="button"
                                    class="tf-btn btn-gray text-nowrap add-to-cart"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-price="{{ $item->price }}"
                                    data-image="{{ $item->attributes->image }}"
                                    data-url="{{ $item->attributes->url }}">
                                    <span class="text-white">Add To Cart</span>
                                </button>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="icon-close" style="font-size: 80px; color: #ccc;"></i>
                </div>
                <h3 class="fw-semibold">Your compare list is empty</h3>
                <p class="text-muted my-3">Add some items to compare properties side by side.</p>
                <a href="{{ route('shop') }}" class="tf-btn"><span class="text-white">Continue Shopping</span></a>
            </div>
            @endif
        </div>
    </div>
    @push('js')
    <script>
        $(document).ready(function() {
            $('.tf-compare-info .remove').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                if (!id) return;
                let url = "{{ route('compare.remove', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
    </script>
    @endpush
    <!-- /Compare -->
    {{-- Dynamic sections from Page Builder --}}
    @if(isset($page) && $page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @endif
</x-frontend-layout>
