<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\PageBuilder;

class PageController extends Controller
{

    protected $pageBuilder;

    public function __construct(PageBuilder $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->with(['activeSections', 'seo'])->firstOrFail();

        // Build SEO meta tags
        $seo = $page->seo;
        $seotags = '';
        if ($seo) {
            $title = $seo->meta_title ?? $page->title;
            $desc  = $seo->meta_description ?? $page->meta_description ?? '';
            $seotags  = '<title>' . e($title) . '</title>';
            $seotags .= '<meta name="description" content="' . e($desc) . '">';
            if (!empty($seo->meta_keywords)) {
                $seotags .= '<meta name="keywords" content="' . e($seo->meta_keywords) . '">';
            }
        } else {
            $seotags = '<title>' . e($page->title) . ' - ' . e(config('app.name')) . '</title>';
            if ($page->meta_description) {
                $seotags .= '<meta name="description" content="' . e($page->meta_description) . '">';
            }
        }

        // Breadcrumbs JSON-LD
        $breadcrumb_list = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $page->title, 'url' => url('/' . $slug)],
        ];
        $items = collect($breadcrumb_list)->map(fn($item, $i) => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $item['name'],
            'item'     => $item['url'],
        ]);
        $breadcrumbs = '<script type="application/ld+json">' . json_encode([
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ]) . '</script>';

        return view('frontend.pages.show', compact('page', 'seotags', 'breadcrumbs', 'breadcrumb_list'));
    }

    /**-----------------------------------------------------------
     * BACKEND
     * -----------------------------------------------------------
     */
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('backend.page-builder.index', compact('pages'));
    }

    public function create()
    {
        return view('backend.page-builder.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'meta_description' => 'nullable|string',
            'layout' => 'required|string',
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => $request->slug ?? Str::slug($request->title),
            'meta_description' => $request->meta_description,
            'layout' => $request->layout,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('page-builder.admin.pages.builder', $page->id)
            ->with('success', 'Page created successfully! You can now add sections.');
    }

    public function edit(Page $page)
    {
        return view('backend.page-builder.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'meta_description' => 'nullable|string',
            'layout' => 'required|string',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'meta_description' => $request->meta_description,
            'layout' => $request->layout,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('page-builder.admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('page-builder.admin.pages.index')
            ->with('success', 'Page deleted successfully!');
    }

    public function builder(Page $page)
    {
        $sectionTypes = $this->pageBuilder->getSectionTypes();
        return view('backend.page-builder.builder', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
            'pageBuilder' => $this->pageBuilder
        ]);
    }

    public function publish(Page $page)
    {
        $page->update(['status' => true]);
        return back()->with('success', 'Page published successfully!');
    }

    public function unpublish(Page $page)
    {
        $page->update(['status' => false]);
        return back()->with('success', 'Page unpublished successfully!');
    }

}
