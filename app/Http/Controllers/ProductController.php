<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Helpers\ImageHelper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Warranty;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\ProductDiscount;
use App\Models\ProductShipping;
use App\Models\ProductTag;
use App\Models\ShippingClass;
use App\Models\Media;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id','desc')->paginate(2);
        return view('backend.inventory.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->where('status', 1)->get();
        $brands = Brand::orderBy('name')->where('status', 1)->get();
        $tags = Tag::orderBy('name')->get();
        $units = Unit::orderBy('name')->where('status', 1)->get();
        $attributes = Attribute::orderBy('name')->where('status', 1)->get();
        $warranties = Warranty::where('status', 1)->get();
        $shippingClasses = ShippingClass::orderBy('id','asc')->where('status', 1)->get();

        return view('backend.inventory.products.create', compact('categories', 'brands', 'tags', 'units', 'attributes', 'warranties','shippingClasses'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        // Handle specifications
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $specs = [];
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key)) {
                    $specs[$key] = $request->spec_values[$index] ?? '';
                }
            }
            $data['specification'] = !empty($specs) ? $specs : null;
        }

        DB::transaction(function() use ($data, $request) {
            // Upload main image
            if ($request->hasFile('main_image')) {
                $data['main_image'] = ImageHelper::uploadImage($request->file('main_image'), 'uploads/products/main', null, null, null, true);
            }
            // Upload hover image
            if ($request->hasFile('hover_image')) {
                $data['hover_image'] = ImageHelper::uploadImage($request->file('hover_image'), 'uploads/products/hover', null, null, null, true);
            }
            // 1. Create Product
            $product = Product::create($data);

            // -------------------------
            // 2. Gallery Images (Media)
            // -------------------------
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $size = $image->getSize();
                    $mime = $image->getMimeType();
                    $path = ImageHelper::uploadImage($image, 'uploads/products/gallery', null, null, null, true);

                    $product->media()->create([
                        'name'       => pathinfo($path, PATHINFO_FILENAME),
                        'path'       => $path,
                        'type'       => Media::getTypeFromMime($mime),
                        'size'       => $size,
                        'user_id'    => Auth::user()->id,
                        'sort_order' => 0,
                    ]);
                }
            }


            // 2. Variants
            if (!empty($data['variants'])) {
                foreach ($data['variants']['variant_sku'] as $i => $variant_sku) {
                    $itemIds = collect($data['attribute_items'] ?? [])->pluck($i)->filter()->values()->all(); // [0, 1, 2...]

                    $variant = $product->variants()->create([
                        'variant_sku'   => $variant_sku,
                        'variant_price' => $data['variants']['variant_price'][$i] ?? 0,
                        'purchase_cost' => $data['variants']['purchase_cost'][$i] ?? 0,
                        'variant_stock' => $data['variants']['variant_stock'][$i] ?? 0,
                        'attribute_item_ids' => $itemIds
                    ]);

                    // 3. Attribute items for this variant
                    if (!empty($data['attribute_items'])) {
                        foreach ($data['attribute_items'] as $attrId => $items) {
                            foreach ($items as $itemId) {
                                // FIX: Get the specific image file from the request
                                $imageFile = $request->file("attribute_images.$attrId.$itemId");

                                $variant->variantItems()->create([
                                    'attribute_id' => $attrId,
                                    'attribute_item_id' => $itemId,
                                    'image' => $imageFile ? ImageHelper::uploadImage($imageFile, 'uploads/variant', null, null, null, true) : null
                                ]);
                            }
                        }
                    }
                }
            }

            // 3. Create Discount
            $discountData = array_filter(
                Arr::only($data, ['discount_type','amount','start_date','end_date']) + ['status' => (int)($data['discount_status'] ?? 0)],
                fn($v) => $v !== null // Keep 0
            );
            if ($discountData) {
                $product->discount()->create($discountData);
            }

            // 4. Create Shipping
            $shippingData = array_filter(
                Arr::only($data, ['weight', 'length', 'width', 'height', 'shipping_cost', 'shipping_class_id', 'inside_city_rate', 'outside_city_rate', 'free_shipping']),
                fn($value) => $value !== null
            );
            if ($shippingData) {
                $product->shipping()->create($shippingData);
            }

            // 5. Create SEO
            if ($request->hasFile('meta_image')) {
                $data['og_image'] = ImageHelper::uploadImage($request->file('meta_image'), 'uploads/seo', null, null, null, true);
            }
            $seoData = array_filter(Arr::only($data, ['meta_title', 'meta_description', 'meta_keywords', 'og_image']));
            if ($seoData) {
                $product->seo ? $product->seo()->update($seoData) : $product->seo()->create($seoData);
            }

        });

        // Redirect
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->where('status', 1)->get();
        $brands = Brand::orderBy('name')->where('status', 1)->get();
        $tags = Tag::orderBy('name')->get();
        $units = Unit::where('status', 1)->get();
        $attributes = Attribute::orderBy('name')->where('status', 1)->get();
        $warranties = Warranty::where('status', 1)->get();
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('id', 'asc')->get();

        $product->load([
            'variants.variantItems.attribute',
            'variants.variantItems.attributeItem',
            'shipping',
            'discount',
            'seo'
        ]);

        // ------------------------------------------
        // SELECTED ATTRIBUTE IDS
        // ------------------------------------------
        $selectedAttributeIds = $product->variants
            ->pluck('variantItems.*.attribute_id')
            ->flatten()
            ->unique()
            ->values();

        // ------------------------------------------
        // SELECTED ATTRIBUTE ITEM IDS (group by attribute)
        // ------------------------------------------
        $selectedItems = [];  // <-- needed by your blade

        foreach ($product->variants as $variant) {
            foreach ($variant->variantItems as $item) {
                $selectedItems[$item->attribute_id][] = $item->attribute_item_id;
            }
        }

        // remove duplicates
        foreach ($selectedItems as $attrId => $items) {
            $selectedItems[$attrId] = array_unique($items);
        }

        // ------------------------------------------
        // Existing Attribute Images (if you store them)
        // Structure:  $existingImages[attribute_id][item_id] = "path/to/img.jpg"
        // ------------------------------------------
        $existingImages = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->variantItems as $item) {
                if ($item->image) {
                    $existingImages[$item->attribute_id][$item->attribute_item_id] = $item->image;
                }
            }
        }

        return view('backend.inventory.products.edit', compact(
            'product',
            'categories',
            'brands',
            'tags',
            'units',
            'attributes',
            'warranties',
            'shippingClasses',
            'selectedAttributeIds',
            'selectedItems',          // FIXED
            'existingImages'          // FIXED
        ));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        
        // Handle specifications
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $specs = [];
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key)) {
                    $specs[$key] = $request->spec_values[$index] ?? '';
                }
            }
            $data['specification'] = !empty($specs) ? $specs : null;
        } else {
            $data['specification'] = null;
        }

        // -------------------------
        // 1. UPDATE PRODUCT IMAGES
        // -------------------------
        if ($request->hasFile('main_image')) {
            $data['main_image'] = ImageHelper::uploadImage($request->file('main_image'), 'uploads/products/main', $product->main_image, null, null, true);
        } elseif ($request->delete_main_image == "1") {
            ImageHelper::deleteImage($product->main_image);
            $data['main_image'] = null;
        }

        if ($request->hasFile('hover_image')) {
            $data['hover_image'] = ImageHelper::uploadImage($request->file('hover_image'), 'uploads/products/hover', $product->hover_image, null, null, true);
        } elseif ($request->delete_hover_image == "1") {
            ImageHelper::deleteImage($product->hover_image);
            $data['hover_image'] = null;
        }

        // Handle deleted media
        if ($request->has('deleted_media')) {
            foreach ($request->deleted_media as $mediaId) {
                $media = Media::find($mediaId);
                if ($media) {
                    ImageHelper::deleteImage($media->path);
                    $media->delete();
                }
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $size = $image->getSize();
                $mime = $image->getMimeType();
                $path = ImageHelper::uploadImage($image, 'uploads/products/gallery', null, null, null, true);

                $product->media()->create([
                    'name'       => pathinfo($path, PATHINFO_FILENAME),
                    'path'       => $path,
                    'type'       => Media::getTypeFromMime($mime),
                    'size'       => $size,
                    'user_id'    => Auth::user()->id,
                    'sort_order' => 0,
                ]);
            }
        }

        // -------------------------
        // 2. UPDATE PRODUCT
        // -------------------------
        $product->update($data);

        // -------------------------
        // 2. UPDATE VARIANTS
        // -------------------------
        if ($request->has('variants') && isset($request->variants['variant_sku'])) {

            $incomingSKUs = $request->variants['variant_sku'];
            $updatedVariantIDs = [];

            foreach ($incomingSKUs as $i => $variant_sku) {
                $itemIds = collect($request->attribute_items ?? [])->pluck($i)->filter()->values()->all();

                // Update or create variant SKU
                $variant = $product->variants()->updateOrCreate(
                    ['variant_sku' => $variant_sku],
                    [
                        'variant_price'     => $request->variants['variant_price'][$i] ?? 0,
                        'purchase_cost'     => $request->variants['purchase_cost'][$i] ?? 0,
                        'variant_stock'     => $request->variants['variant_stock'][$i] ?? 0,
                        'attribute_item_ids'=> $itemIds,
                    ]
                );
                $updatedVariantIDs[] = $variant->id;

                // -----------------------------
                // UPDATE VARIANT ITEMS + IMAGES
                // -----------------------------
                if (!empty($request->attribute_items)) {

                    $incomingPairs = [];

                    foreach ($request->attribute_items as $attrId => $items) {
                        foreach ($items as $itemId) {
                            $incomingPairs[] = $attrId.'-'.$itemId;
                            $oldItem = $variant->variantItems()->where('attribute_id', $attrId)->where('attribute_item_id', $itemId)->first();
                            $newImage = $request->file("attribute_images.$attrId.$itemId");
                            $finalImage = ImageHelper::uploadImage($newImage, 'uploads/variant', $oldItem->image ?? null, null, null, true);

                            $variant->variantItems()->updateOrCreate(
                                ['attribute_id' => $attrId, 'attribute_item_id' => $itemId],
                                ['image' => $finalImage]
                            );
                        }
                    }

                    // Delete old items not in incomingPairs
                    $variant->variantItems()->get()->each(function($item) use ($incomingPairs) {
                        if (!in_array($item->attribute_id.'-'.$item->attribute_item_id, $incomingPairs)) {
                            ImageHelper::deleteImage($item->image);
                            $item->delete();
                        }
                    });
                }
            }

            // -----------------------------
            // DELETE VARIANTS NOT IN REQUEST
            // -----------------------------
            $product->variants()->whereNotIn('id', $updatedVariantIDs)->get()->each(function($variant){
                $variant->variantItems->each(fn($item) => ImageHelper::deleteImage($item->image));
                $variant->delete();
            });
        }

        // -------------------------
        // 3. UPDATE DISCOUNT
        // -------------------------
        $discountData = array_filter(
            Arr::only($data, ['discount_type','amount','start_date','end_date'])
            + ['status' => (int)($data['discount_status'] ?? 0)],
            fn($v) => $v !== null
        );

        if ($discountData) {
            $product->discount
                ? $product->discount()->update($discountData)
                : $product->discount()->create($discountData);
        }

        // -------------------------
        // 4. UPDATE SHIPPING
        // -------------------------
        $shippingData = array_filter(
            Arr::only($data, [
                'weight','length','width','height','shipping_cost',
                'shipping_class_id','inside_city_rate','outside_city_rate','free_shipping'
            ]),
            fn($v) => $v !== null
        );

        if ($shippingData) {
            $product->shipping
                ? $product->shipping()->update($shippingData)
                : $product->shipping()->create($shippingData);
        }

        // -------------------------
        // 5. UPDATE SEO IMAGE
        // -------------------------
        $seoData = Arr::only($data, ['meta_title','meta_description','meta_keywords']);
        $metaImage = $request->file('meta_image');

        $seoData['og_image'] = ImageHelper::uploadImage(
            $metaImage,
            'uploads/seo',
            optional($product->seo)->og_image,
            null,
            null,
            true
        );

        // Handle delete meta image request
        if (!empty($data['delete_meta_image'])) {
            ImageHelper::deleteImage(optional($product->seo)->og_image);
            $seoData['og_image'] = null;
        }

        if ($seoData) {
            $product->seo
                ? $product->seo()->update($seoData)
                : $product->seo()->create($seoData);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * ----------------------------------------------------------------------
     * Get attribute items based on selected attribute IDs
     * ----------------------------------------------------------------------
     */
    public function getItems(Request $request)
    {
        $attributeIds = $request->get('attribute_ids', []);
        if (empty($attributeIds)) return '';

        $attributes = Attribute::with('items')->whereIn('id', $attributeIds)->get();

        $selectedItems = [];
        $existingImages = [];

        if ($request->has('product_id')) {
            $product = Product::with('variants.variantItems')->find($request->product_id);

            if ($product) {
                foreach ($product->variants as $variant) {
                    foreach ($variant->variantItems as $item) {
                        $selectedItems[$item->attribute_id][] = $item->attribute_item_id;
                        if ($item->image) $existingImages[$item->attribute_id][$item->attribute_item_id] = $item->image;
                    }
                }
                foreach ($selectedItems as $attrId => $items) $selectedItems[$attrId] = array_unique($items);
            }
        }

        // dd($product->variants);

        return view('backend.inventory.products.partials._attribute_items', compact('attributes','selectedItems','existingImages'))->render();
    }

    public function getVariantCombinations(Request $request)
    {
        $skuPrefix = $request->input('sku', 'SKU');
        $sale_price = $request->input('sale_price', 0);
        $purchase_price = $request->input('purchase_price', 0);
        $total_stock = $request->input('total_stock', 0);
        $attributes = collect($request->input('attributes', []))->filter(fn($a) => !empty($a['items']))
                        ->map(function($a) {
                            $a['items'] = array_map('intval', $a['items']);
                            return $a;
                        })->values();

        if ($attributes->isEmpty()) {
            return '';
        }

        // Load existing variants if product_id is provided
        $existingVariants = [];
        $productId = $request->input('product_id');
        if ($productId) {
            $product = Product::with('variants.variantItems')->find($productId);
            if ($product) {
                foreach ($product->variants as $variant) {
                    $existingVariants[] = [
                        'variant_sku'   => $variant->variant_sku,
                        'variant_price' => $variant->variant_price,
                        'purchase_cost' => $variant->purchase_cost,
                        'variant_stock' => $variant->variant_stock,
                        'items' => collect($variant->variantItems->pluck('attribute_item_id'))->map(fn($id) => (int)$id)->sort()->values()->all()
                    ];
                }
            }
        }

        // Collect items for cartesian product
        $combos = $this->cartesianProduct($attributes->pluck('items')->toArray());

        $variants = collect($combos)->map(function ($combo) use ($skuPrefix, $sale_price, $purchase_price, $total_stock, $existingVariants) {
            $comboItemIds = array_map('intval', $combo);
            sort($comboItemIds);

            // Get attribute item names
            $names = AttributeItem::whereIn('id', $comboItemIds)->pluck('name')->toArray();

            // Generate default SKU for this combination
            $defaultSku = $skuPrefix . '-' . strtolower(implode('-', array_map(fn($n) => str_replace(' ', '-', $n), $names)));

            // Check if this SKU exists in existing variants
            $existing = collect($existingVariants)->first(function ($v) use ($defaultSku) {
                return $v['variant_sku'] === $defaultSku;
            });

            return [
                'name' => implode(' | ', $names),
                'variant_sku'   => $existing['variant_sku'] ?? $defaultSku,
                'variant_price' => $existing['variant_price'] ?? $sale_price,
                'purchase_cost' => $existing['purchase_cost'] ?? ($purchase_price > 0 ? $purchase_price : $sale_price * 0.75),
                'variant_stock' => $existing['variant_stock'] ?? $total_stock,
                'items' => $comboItemIds
            ];
        });

        return view('backend.inventory.products.partials._variant_table', ['variants' => $variants])->render();
    }


    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $propertyValues) {
            $tmp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $tmp[] = array_merge($resultItem, [(int)$propertyValue]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    /**------------------------------------------------------------------------------------------------
     * Expired Products
     * ------------------------------------------------------------------------------------------------
     */
    public function expiredIndex(Request $request)
    {
        $expiredProducts = Product::where('expire_date', '<=', now()->toDateString())
            ->where('status', 1)
            ->orderBy('expire_date', 'asc')
            ->get();

        return view('backend.inventory.expired.index', compact('expiredProducts'));
    }
    public function handleExpired($id)
    {
        $product = Product::findOrFail($id);

        $product->status = 0;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product marked as handled successfully.'
        ]);
    }
    public function restoreExpired(Request $request, $id)
    {
        $request->validate([
            'expire_date' => 'required|date|after:today',
        ]);

        $product = Product::findOrFail($id);

        $product->expire_date = $request->expire_date;
        $product->status = 1;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully.'
        ]);
    }
    /**------------------------------------------------------------------------------------------------
     * Low Stock
     * ------------------------------------------------------------------------------------------------
     */

    public function lowStocksIndex(Request $request)
    {
        // Fetch products where stock is less than alert quantity or stock = 0
        $lowStocks = Product::where('status', 1)
            ->where(function($q) {
                $q->whereColumn('total_stock', '<=', 'alert_quantity')
                ->orWhere('total_stock', '=', 0);
            })
            ->orderBy('total_stock', 'asc')
            ->get();

        return view('backend.inventory.low-stocks.index', compact('lowStocks'));
    }

    public function notifyLowStock(Request $request)
    {
        $productIds = $request->input('products', []);

        if(empty($productIds)) {
            return response()->json(['success' => false, 'message' => 'No product selected']);
        }

        $products = Product::whereIn('id', $productIds)->get();

        // Example: Send notification logic here (email/SMS)
        foreach ($products as $product) {
            // Mail::to('admin@example.com')->send(new LowStockAlert($product));
        }

        return response()->json(['success' => true, 'message' => 'Notification sent successfully']);
    }



    /**------------------------------------------------------------------------------------------------
     * Low Stock
     * ------------------------------------------------------------------------------------------------
     */
    public function labelPrintIndex()
    {
        $stores = ['Electro Mart', 'Quantum Gadgets', 'Prime Bazaar', 'Gadget World', 'Volt Vault'];
        return view('backend.inventory.label-print.index', compact('stores'));
    }

    public function labelPrintSearch(Request $request)
    {
        $q = $request->get('q');

        return Product::where('name', 'like', "%{$q}%")
            ->orWhere('sku', 'like', "%{$q}%")
            ->select('id', 'name', 'sku', 'sale_price')
            ->limit(10)
            ->get();
    }

    public function labelPrintGenerate(Request $request)
    {
        return $this->processLabelGeneration($request, 'barcode');
    }

    public function labelPrintGenerateQR(Request $request)
    {
        return $this->processLabelGeneration($request, 'qrcode');
    }

    private function processLabelGeneration(Request $request, $type)
    {
        // 1. Decode product data sent from frontend
        $productsData = json_decode($request->products, true);
        if (!$productsData) {
            return back()->with('error', 'No products selected');
        }

        // 2. Prepare formatting options
        $store = $request->store ?? 'Our Store'; // Default to 3 columns
        $columns = $request->columns ?? 3; // Default to 3 columns
        $paperSize = $request->paper_size ?? 'A4';

        $options = [
            'show_store'   => $request->show_store == 1,
            'show_product' => $request->show_product == 1,
            'show_price'   => $request->show_price == 1,
            'store_name'   => $store,
            'type'         => $type,
            'columns'      => $columns,
        ];

        // 3. Flatten the array based on Quantity
        $labels = [];
        foreach ($productsData as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                for ($i = 0; $i < $item['qty']; $i++) {
                    $labels[] = [
                        'name'  => $product->name,
                        'sku'   => $product->sku,
                        'price' => $item['price'] ?? $product->sale_price,
                    ];
                }
            }
        }

        // 4. Load View and Pass Barcode Generators
        $pdf = Pdf::loadView('backend.inventory.label-print.label-print-pdf', [
            'labels'  => $labels,
            'config'  => $options,
            'dns1'    => new DNS1D(),
            'dns2'    => new DNS2D(),
        ]);

        // 5. Set Paper Size (A4, A3, etc.)
        $pdf->setPaper($paperSize, 'portrait');

        return $pdf->stream($type . "_labels.pdf");
    }

    public function mediaUpload(Request $request)
    {
        $file = $request->file('file');
        $type = $request->input('type', 'gallery'); // main, hover, gallery, etc.

        $folder = 'uploads/products/' . $type;
        $path = ImageHelper::uploadImage($file, $folder, null, null, null, true);

        return response()->json([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientOriginalExtension(),
            'full_path' => asset($path)
        ]);
    }

    public function mediaDelete(Request $request)
    {
        $path = $request->input('path');
        if ($path) {
            ImageHelper::deleteImage($path);

            // If it's a permanent media record, delete it from DB
            Media::where('path', $path)->delete();
        }

        return response()->json(['success' => true]);
    }

}
