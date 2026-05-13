<?php

namespace App\Http\Controllers;

use App\Models\HomeSlide;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class HomeSlideController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::orderBy('sort_order')->orderBy('id', 'desc')->paginate(15);
        return view('backend.home-slides.index', compact('slides'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'nullable|string|max:255',
            'offer_text'     => 'nullable|string|max:255',
            'details'        => 'nullable|string',
            'button_text'    => 'nullable|string|max:100',
            'button_url'     => 'nullable|string|max:500',
            'sort_order'     => 'nullable|integer|min:0',
            'desktop_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'mobile_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'offer_text', 'details', 'button_text', 'button_url', 'sort_order']);
        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = ImageHelper::uploadImage($request->file('desktop_image'), 'uploads/home-slides/desktop', null, null, null, true);
        }
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = ImageHelper::uploadImage($request->file('mobile_image'), 'uploads/home-slides/mobile', null, null, null, true);
        }

        HomeSlide::create($data);

        return redirect()->route('home-slides.index')->with('success', 'Home slide created successfully.');
    }


    public function update(Request $request, HomeSlide $homeSlide)
    {
        $request->validate([
            'title'          => 'nullable|string|max:255',
            'offer_text'     => 'nullable|string|max:255',
            'details'        => 'nullable|string',
            'button_text'    => 'nullable|string|max:100',
            'button_url'     => 'nullable|string|max:500',
            'sort_order'     => 'nullable|integer|min:0',
            'desktop_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'mobile_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'offer_text', 'details', 'button_text', 'button_url', 'sort_order']);
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = ImageHelper::uploadImage($request->file('desktop_image'), 'uploads/home-slides/desktop', $homeSlide->desktop_image, null, null, true);
        } elseif ($request->boolean('remove_desktop_image')) {
            ImageHelper::deleteImage($homeSlide->desktop_image);
            $data['desktop_image'] = null;
        }

        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = ImageHelper::uploadImage($request->file('mobile_image'), 'uploads/home-slides/mobile', $homeSlide->mobile_image, null, null, true);
        } elseif ($request->boolean('remove_mobile_image')) {
            ImageHelper::deleteImage($homeSlide->mobile_image);
            $data['mobile_image'] = null;
        }

        $homeSlide->update($data);

        return redirect()->route('home-slides.index')->with('success', 'Home slide updated successfully.');
    }

    public function destroy(HomeSlide $homeSlide)
    {
        ImageHelper::deleteImage($homeSlide->desktop_image);
        ImageHelper::deleteImage($homeSlide->mobile_image);
        $homeSlide->delete();

        return redirect()->route('home-slides.index')->with('success', 'Home slide deleted successfully.');
    }
}
