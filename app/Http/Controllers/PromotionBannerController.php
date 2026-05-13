<?php

namespace App\Http\Controllers;

use App\Models\PromotionBanner;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class PromotionBannerController extends Controller
{
    public function index()
    {
        $banners = PromotionBanner::orderBy('sort_order')->orderBy('id', 'desc')->paginate(15);
        return view('backend.promotion-banners.index', compact('banners'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'details'     => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'url'         => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'details', 'button_text', 'url', 'sort_order']);
        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::uploadImage($request->file('image'), 'uploads/promotion-banners', null, null, null, true);
        }

        PromotionBanner::create($data);

        return redirect()->route('promotion-banners.index')->with('success', 'Promotion banner created successfully.');
    }


    public function update(Request $request, PromotionBanner $promotionBanner)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'details'     => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'url'         => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'details', 'button_text', 'url', 'sort_order']);
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::uploadImage($request->file('image'), 'uploads/promotion-banners', $promotionBanner->image, null, null, true);
        } elseif ($request->boolean('remove_image')) {
            ImageHelper::deleteImage($promotionBanner->image);
            $data['image'] = null;
        }

        $promotionBanner->update($data);

        return redirect()->route('promotion-banners.index')->with('success', 'Promotion banner updated successfully.');
    }

    public function destroy(PromotionBanner $promotionBanner)
    {
        ImageHelper::deleteImage($promotionBanner->image);
        $promotionBanner->delete();

        return redirect()->route('promotion-banners.index')->with('success', 'Promotion banner deleted successfully.');
    }
}
