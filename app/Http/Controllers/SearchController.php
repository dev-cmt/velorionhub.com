<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $search = $request->get('search');
        
        if (empty($search)) {
            return response()->json([]);
        }

        $products = Product::where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%');
            })
            ->active()
            ->take(5)
            ->get();

        return view('frontend.partials.search-suggestions', [
            'products' => $products,
            'query'    => $search,
        ])->render();
    }
}
