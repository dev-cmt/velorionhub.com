<?php

namespace App\Http\Controllers\Theme;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\SeoTrait;
use App\Models\Product;
use App\Models\Page;
use App\Models\Category;

class Theme1Controller extends Controller
{
    use SeoTrait;

    public function welcome()
    {
        $products = Product::with('media')->withCount('reviews')->withAvg('reviews', 'rating')->active()->get();

        // SEO
        $page = Page::with('seo')->where('slug','home')->firstOrFail();
        $seotags = $this->applySeo($page);

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        $categories = Category::with('media')->where('is_home', true)->where('status', true)->take(8)->get();

        $best_sellers = $products->shuffle()->take(10);
        $new_arrivals = $products->sortByDesc('created_at')->take(10);
        return view('frontend.welcome', compact('seotags','breadcrumbs', 'products', 'categories', 'best_sellers', 'new_arrivals'));
    }

    public function shop()
    {
        // SEO
        $page = Page::with('seo')->where('slug','shop')->first();
        $seotags = $this->applySeo($page, 'Shop - ' . config('app.name'));

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        return view('frontend.shop', compact('seotags','breadcrumbs'));
    }

    public function productShow($slug)
    {
        $product = Product::with([
            'media', 
            'brand', 
            'category',
            'variants.variantItems.attribute',
            'variants.variantItems.attributeItem'
        ])
        ->withCount('reviews')        // review count
        ->withAvg('reviews', 'rating') // average rating
        ->where('slug', $slug)->firstOrFail();

        // SEO
        $seotags = $this->applySeo($product);

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);

        $related_products = Product::with('media', 'brand', 'category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(10)
            ->get();

        return view('frontend.product-details', compact('seotags','breadcrumbs', 'product', 'related_products'));
    }

    public function checkout()
    {
        // SEO
        $page = Page::with('seo')->where('slug','checkout')->first();
        $seotags = $this->applySeo($page, 'Checkout - ' . config('app.name'));

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        return view('frontend.checkout', compact('seotags','breadcrumbs'));
    }

    public function cart()
    {
        // SEO
        $page = Page::with('seo')->where('slug','cart')->first();
        $seotags = $this->applySeo($page, 'Cart - ' . config('app.name'));

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        return view('frontend.cart', compact('seotags','breadcrumbs'));
    }

    public function wishlist()
    {
        // SEO
        $page = Page::with('seo')->where('slug','wishlist')->first();
        $seotags = $this->applySeo($page, 'Wishlist - ' . config('app.name'));

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        return view('frontend.wishlist', compact('seotags','breadcrumbs'));
    }

    public function compare()
    {
        // SEO
        $page = Page::with('seo')->where('slug','compare')->first();
        $seotags = $this->applySeo($page, 'Compare - ' . config('app.name'));

        $breadcrumbs = $this->generateBreadcrumbJsonLd([
            ['name' => 'Home', 'url' => url('/')],
        ]);
        return view('frontend.compare', compact('seotags','breadcrumbs'));
    }

}
