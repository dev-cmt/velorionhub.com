{{-- Products Showcase Section --}}
@php
    use App\Models\Product;
    $layout = $content['layout'] ?? 'grid-4';
    $limit  = intval($content['limit'] ?? 8);
    $featuredOnly = !empty($content['featured_only']);
    $productIds = $content['product_ids'] ?? [];

    // Build query
    $query = Product::query()->where('status', 1);
    if (!empty($productIds)) {
        $query->whereIn('id', $productIds);
    } elseif ($featuredOnly) {
        $query->where('is_featured', 1);
    }
    $products = $query->latest()->take($limit)->get();

    $colClass = match($layout) {
        'grid-2' => 'col-lg-6 col-md-6',
        'grid-3' => 'col-lg-4 col-md-6',
        default  => 'col-lg-3 col-md-6',
    };
@endphp
<section class="pb-products-section tf-sp-2">
    <div class="container">
        @if(!empty($content['title']))
            <div class="text-center mb-5">
                <h2 class="fw-semibold">{{ $content['title'] }}</h2>
                @if(!empty($content['description']))
                    <p class="text-muted mt-2">{{ $content['description'] }}</p>
                @endif
            </div>
        @endif
        @if($products->isNotEmpty())
        <div class="row g-4">
            @foreach($products as $product)
            <div class="{{ $colClass }}">
                @include('frontend.partials.product-item', ['product' => $product])
            </div>
            @endforeach
        </div>
        @else
            <p class="text-center text-muted">No products found for this section.</p>
        @endif
    </div>
</section>
