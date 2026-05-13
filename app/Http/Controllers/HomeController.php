<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\SeoTrait;
use App\Models\Product;
use App\Models\Page;
use App\Models\HomeSlide;
use App\Models\PromotionBanner;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    use SeoTrait;

    public function index()
    {
        $products = Product::with('media')->withCount('reviews')->withAvg('reviews', 'rating')->active()->get();
        $hot_deals = $products->take(10);

        $slides = HomeSlide::query()
            ->where('status', true)
            ->whereNotNull('desktop_image')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $promotionBanners = PromotionBanner::query()
            ->where('status', true)
            ->whereNotNull('image')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // SEO
        $page = Page::with('seo')->where('slug','home')->firstOrFail();
        $seotags = $this->applySeo($page);

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);
        $categories = Category::with('media')->where('is_home', true)->where('status', true)->take(8)->get();

        $best_sellers = $products->shuffle()->take(10);
        $new_arrivals = Product::with('media')->withCount('reviews')->withAvg('reviews', 'rating')->active()->latest()->take(10)->get();
        $top_rated = $products->sortByDesc('reviews_avg_rating')->take(3);
        $special_offers = $products->filter(function($p) {
            return $p->sale_price < $p->regular_price;
        })->take(3);
        $column_bestsellers = $products->shuffle()->take(3);
        $brands = Brand::where('status', true)->orderBy('sort_order')->get();
        $latest_posts = BlogPost::with('author')->where('status', 'published')->latest()->take(10)->get();

        return view('frontend.index', compact(
            'seotags',
            'breadcrumbs', 
            'breadcrumb_list',
            'products', 
            'hot_deals', 
            'slides', 
            'promotionBanners', 
            'categories', 
            'best_sellers', 
            'new_arrivals', 
            'top_rated', 
            'special_offers', 
            'column_bestsellers', 
            'brands',
            'latest_posts'
        ));
    }

    public function shop(Request $request)
    {
        $query = Product::with('media')->withCount('reviews')->withAvg('reviews', 'rating')->active();

        // Filtering
        if ($request->has('category') && !empty($request->category)) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->has('brand') && !empty($request->brand)) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('min_price')) {
            $query->where('sale_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('sale_price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('status', true)->whereNull('parent_id')->with('children')->get();
        $brands = Brand::where('status', true)->get();

        // SEO
        $page = Page::with('seo')->where('slug', 'shop')->first(); 
        $seotags = $this->applySeo($page, 'Shop - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Shop', 'url' => route('shop')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        $latest_products = Product::with('media')->active()->latest()->take(5)->get();

        return view('frontend.shop', compact('seotags', 'breadcrumbs', 'breadcrumb_list', 'products', 'categories', 'brands', 'latest_products'));
    }

    public function productShow($slug)
    {
        $product = Product::with('media', 'brand', 'category')
        ->withCount('reviews')        // review count
        ->withAvg('reviews', 'rating') // average rating
        ->where('slug', $slug)->firstOrFail();

        // SEO
        $seotags = $this->applySeo($product);

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Shop', 'url' => route('shop')],
            ['name' => $product->name, 'url' => route('product.show', $product->slug)],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        $related_products = Product::with('media', 'brand', 'category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(10)
            ->get();

        return view('frontend.product-details', compact('seotags','breadcrumbs', 'breadcrumb_list', 'product', 'related_products'));
    }

    public function checkout()
    {
        // SEO
        $page = Page::with('seo')->where('slug','checkout')->first();
        $seotags = $this->applySeo($page, 'Checkout - ' . config('app.name'));


        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Checkout', 'url' => route('checkout')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);
        return view('frontend.checkout', compact('seotags','breadcrumbs', 'breadcrumb_list'));
    }

    public function cart()
    {
        // SEO
        $page = Page::with('seo')->where('slug','cart')->first();
        $seotags = $this->applySeo($page, 'Cart - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Cart', 'url' => route('cart')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);
        return view('frontend.cart', compact('seotags','breadcrumbs', 'breadcrumb_list'));
    }

    public function wishlist()
    {
        // SEO
        $page = Page::with('seo')->where('slug','wishlist')->first();
        $seotags = $this->applySeo($page, 'Wishlist - ' . config('app.name'));


        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Wishlist', 'url' => route('wishlist')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);
        return view('frontend.wishlist', compact('seotags','breadcrumbs', 'breadcrumb_list'));
    }

    public function compare()
    {
        // SEO
        $page = Page::with('seo')->where('slug','compare')->first();
        $seotags = $this->applySeo($page, 'Compare - ' . config('app.name'));


        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Compare', 'url' => route('compare')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);
        return view('frontend.compare', compact('seotags','breadcrumbs', 'breadcrumb_list'));
    }

    public function blog(Request $request)
    {
        $query = BlogPost::with(['category', 'author', 'media'])->published()->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $blog = $query->paginate(10);

        // SEO
        $page = Page::with('seo')->where('slug', 'blog')->first(); 
        $seotags = $this->applySeo($page, 'Blog - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        // Sidebar data
        $blog_categories = Category::whereHas('blogPosts')->get();
        $latest_posts = BlogPost::published()->latest()->take(5)->get();
        $blog_tags = Tag::whereHas('blogPosts')->get();

        return view('frontend.blog', compact('blog', 'seotags', 'breadcrumbs', 'breadcrumb_list', 'blog_categories', 'latest_posts', 'blog_tags'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // You can add logic to save the email here
        
        return back()->with('success', 'Thank you for subscribing!');
    }

    public function blogShow($slug)
    {
        $post = BlogPost::with('author', 'category', 'tags')->where('slug', $slug)->firstOrFail();
        
        // SEO
        $seotags = $this->applySeo($post);
        $jsonld = $this->generateArticleJsonLd($post);

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog')],
            ['name' => $post->title, 'url' => url()->current()],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.blog-details', compact('seotags', 'breadcrumbs', 'breadcrumb_list', 'post', 'jsonld'));
    }


    public function catalog()
    {
        $categories = Category::with('media')
            ->where('status', true)
            ->whereNull('parent_id')
            ->withCount('product')
            ->get();

        // SEO
        $page = Page::with('seo')->where('slug', 'catalog')->first();
        $seotags = $this->applySeo($page, 'Catalog - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Catalog', 'url' => route('catalog')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.catalog', compact('categories', 'seotags', 'breadcrumbs', 'breadcrumb_list'));
    }

    public function catalogShow($slug)
    {
        $category = Category::with('children.media')->withCount('product')->where('slug', $slug)->firstOrFail();

        if ($category->children->count() > 0) {
            $categories = $category->children;
            foreach ($categories as $child) {
                $child->loadCount('product');
            }

            // SEO
            $seotags = $this->applySeo($category, $category->name . ' - Catalog - ' . config('app.name'));

            $breadcrumb_list = [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Catalog', 'url' => route('catalog')],
                ['name' => $category->name, 'url' => route('catalog.show', $category->slug)],
            ];
            $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

            return view('frontend.catalog', compact('categories', 'seotags', 'breadcrumbs', 'breadcrumb_list', 'category'));
        }

        return redirect()->route('shop', ['category' => $category->slug]);
    }

    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'text' => 'required|string|max:1000',
        ]);

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(), // Nullable if guest
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'comment' => $request->text,
            'status' => 0, // Pending approval by default
        ]);

        return redirect()->back()->with('success', 'Your review has been submitted and is waiting for approval.');
    }

    public function placeOrder(Request $request)
    {
        $cart = Cart::session(Auth::id() ?? session()->getId());
        if ($cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $payment_method = 3; // cod
        if ($request->payment_method == 'cash') {
            $payment_method = 0;
        } elseif ($request->payment_method == 'cod') {
            $payment_method = 3;
        }

        $order = Order::create([
            'invoice_no' => 'INV-' . strtoupper(uniqid()),
            'source' => 'web',
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address . ', ' . $request->city,
            'sub_total' => $cart->getSubTotal(),
            'shipping_cost' => 0, // Placeholder
            'discount' => 0,
            'total' => $cart->getTotal(),
            'paid' => 0,
            'due' => $cart->getTotal(),
            'payment_method' => $payment_method,
            'payment_status' => 0, // pending
            'status' => 0, // pending
            'notes' => $request->note,
            'customer_id' => Auth::id(),
        ]);

        foreach ($cart->getContent() as $item) {
            // Need product ID from associated model or attribute if available, fallback to 0
            $productId = $item->associatedModel->id ?? $item->id;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'sku' => $item->id,
                'quantity' => $item->quantity,
                'purchase_price' => 0, // Fallback if unknown
                'sale_price' => $item->price,
                'attributes' => $item->attributes->toArray() ?? [],
            ]);
        }

        $cart->clear();

        // Redirect to order confirmation page
        return redirect()->route('order.confirm', ['invoice' => $order->invoice_no]);
    }

    public function orderConfirm($invoice)
    {
        $order = Order::where('invoice_no', $invoice)->with('items.product')->firstOrFail();
        
        $theme = config('theme.frontend.views_path');
        return view($theme . '.order-confirm', compact('order'));
    }

    public function addWishlist(Request $request)
    {
        if (!$request->has('id')) {
            return Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->getTotalQuantity();
        }

        $product = Product::findOrFail($request->id);
        Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->sale_price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg'),
                'url' => route('product.show', $product->slug),
                'stock' => $product->current_stock > 0 ? 'In Stock' : 'Out of Stock',
            ]
        ]);
        return response()->json(['success' => true, 'message' => 'Added to wishlist successfully', 'count' => Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->getTotalQuantity()]);
    }

    public function removeWishlist($id)
    {
        Cart::session((Auth::id() ?? session()->getId()) . '_wishlist')->remove($id);
        return back()->with('success', 'Item removed from wishlist.');
    }

    public function addCompare(Request $request)
    {
        if (!$request->has('id')) {
            return Cart::session((Auth::id() ?? session()->getId()) . '_compare')->getTotalQuantity();
        }

        $product = Product::with('category', 'brand')->findOrFail($request->id);
        Cart::session((Auth::id() ?? session()->getId()) . '_compare')->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->sale_price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->main_image ? asset($product->main_image) : asset('images/no-image.jpg'),
                'url' => route('product.show', $product->slug),
                'stock' => $product->current_stock > 0 ? 'In Stock' : 'Out of Stock',
                'sku' => $product->sku ?? 'N/A',
                'category' => $product->category->name ?? 'Uncategorized',
                'brand' => $product->brand->name ?? 'No Brand',
                'description' => $product->short_description ?? 'No description',
            ]
        ]);
        return response()->json(['success' => true, 'message' => 'Added to compare list successfully', 'count' => Cart::session((Auth::id() ?? session()->getId()) . '_compare')->getTotalQuantity()]);
    }

    public function removeCompare($id)
    {
        Cart::session((Auth::id() ?? session()->getId()) . '_compare')->remove($id);
        return back()->with('success', 'Item removed from compare list.');
    }


    public function aboutUs()
    {
        $page = Page::with('seo')->where('slug', 'about-us')->first();
        $seotags = $this->applySeo($page, 'About Us - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'About Us', 'url' => route('about')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.about-us', compact('seotags', 'breadcrumbs', 'breadcrumb_list'));
    }

    public function contacts()
    {
        $page = Page::with('seo')->where('slug', 'contacts')->first();
        $seotags = $this->applySeo($page, 'Contact Us - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Contact Us', 'url' => route('contacts')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.contacts', compact('seotags', 'breadcrumbs', 'breadcrumb_list'));
    }

    public function trackOrder()
    {
        $page = Page::with('seo')->where('slug', 'track-order')->first();
        $seotags = $this->applySeo($page, 'Track Order - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Track Order', 'url' => route('track.order')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.track-order', compact('seotags', 'breadcrumbs', 'breadcrumb_list'));
    }

    public function faq()
    {
        $page = Page::with('seo')->where('slug', 'faq')->first();
        $seotags = $this->applySeo($page, 'FAQ - ' . config('app.name'));

        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'FAQ', 'url' => route('faq')],
        ];
        $breadcrumbs = $this->generateBreadcrumbJsonLd($breadcrumb_list);

        return view('frontend.faq', compact('seotags', 'breadcrumbs', 'breadcrumb_list'));
    }
}
