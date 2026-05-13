<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories for hierarchical display
        $categories = Category::with('media')->orderBy('id', 'desc')->get();

        $categoriesForJs = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'name' => $category->name,
                'status' => $category->status,
                'image' => $category->image,
                'is_menu' => $category->is_menu,
                'is_home' => $category->is_home,
                'is_section' => $category->is_section,
                'is_footer' => $category->is_footer,
            ];
        });

        return view('backend.inventory.categories.index', [
            'data' => $categories,
            'categoriesForJs' => $categoriesForJs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_menu' => 'nullable|boolean',
            'is_home' => 'nullable|boolean',
            'is_section' => 'nullable|boolean',
            'is_footer' => 'nullable|boolean',
        ]);

        $imageFile = $request->file('image');
        unset($validated['image']);

        // Set boolean defaults for unchecked checkboxes
        $validated['is_menu'] = $validated['is_menu'] ?? 0;
        $validated['is_home'] = $validated['is_home'] ?? 0;
        $validated['is_section'] = $validated['is_section'] ?? 0;
        $validated['is_footer'] = $validated['is_footer'] ?? 0;

        $category = Category::create($validated);

        if ($request->hasFile('image')) {
            $path = ImageHelper::uploadImage($imageFile, 'uploads/category', null, null, null, true);

            $fileSize = 0;
            if ($path && file_exists(public_path($path))) {
                $fileSize = filesize(public_path($path));
            }

            $category->media()->create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'alt_text' => $category->name,
                'size' => $fileSize,
                'sort_order' => 0,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request)
    {
        $category = Category::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_menu' => 'nullable|boolean',
            'is_home' => 'nullable|boolean',
            'is_section' => 'nullable|boolean',
            'is_footer' => 'nullable|boolean',
        ]);

        $imageFile = $request->file('image');
        unset($validated['image']);

        // Set boolean defaults for unchecked checkboxes
        $validated['is_menu'] = $validated['is_menu'] ?? 0;
        $validated['is_home'] = $validated['is_home'] ?? 0;
        $validated['is_section'] = $validated['is_section'] ?? 0;
        $validated['is_footer'] = $validated['is_footer'] ?? 0;

        $category->update($validated);

        if ($request->hasFile('image')) {
            $oldMedia = $category->media()->ordered()->first();
            $oldPath = $oldMedia?->path;

            $path = ImageHelper::uploadImage($imageFile, 'uploads/category', $oldPath, null, null, true);

            $fileSize = 0;
            if ($path && file_exists(public_path($path))) {
                $fileSize = filesize(public_path($path));
            }

            if ($oldMedia) {
                $oldMedia->delete();
            }

            $category->media()->create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'alt_text' => $category->name,
                'size' => $fileSize,
                'sort_order' => 0,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->loadMissing('media');

        foreach ($category->media as $media) {
            ImageHelper::deleteImage($media->path);
            $media->delete();
        }

        // Delete category (subcategories will cascade if foreign key set with onDelete('cascade'))
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
