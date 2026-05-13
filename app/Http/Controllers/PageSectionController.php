<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageSection;
use App\Models\Page;
use App\Services\PageBuilder;

class PageSectionController extends Controller
{
    protected $pageBuilder;

    public function __construct(PageBuilder $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'type' => 'required|string|in:hero,features,products,contact,text,image,gallery,testimonials,team,cta',
            'content' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $page = Page::findOrFail($request->page_id);

        // Get default content based on section type
        $defaultContent = $this->getDefaultContent($request->type);

        $section = PageSection::create([
            'page_id' => $page->id,
            'type' => $request->type,
            'content' => $request->content ?? $defaultContent,
            'settings' => $request->settings ?? [],
            'order' => $page->sections()->count() + 1,
            'status' => true,
        ]);

        return response()->json([
            'success' => true,
            'section' => $section,
            'html' => $this->pageBuilder->renderSection($section)->render()
        ]);
    }

    public function edit(PageSection $section)
    {
        $sectionTypes = $this->pageBuilder->getSectionTypes();
        return view('backend.page-builder.sections.edit', compact('section', 'sectionTypes'));
    }

    public function update(Request $request, PageSection $section)
    {
        $request->validate([
            'content' => 'nullable|array',
            'settings' => 'nullable|array',
            'status' => 'sometimes|boolean',
        ]);

        $section->update([
            'content' => $request->content ?? [],
            'settings' => $request->settings ?? [],
            'status' => $request->has('status') ? $request->status : $section->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'section' => $section,
                'html' => $this->pageBuilder->renderSection($section)->render()
            ]);
        }

        return redirect()->route('page-builder.admin.pages.builder', $section->page_id)
            ->with('success', 'Section updated successfully!');
    }

    public function destroy(PageSection $section)
    {
        $pageId = $section->page_id;
        $section->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('page-builder.admin.pages.builder', $pageId)
            ->with('success', 'Section deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'page_id' => 'required|exists:pages,id',
        ]);

        foreach ($request->sections as $order => $sectionId) {
            PageSection::where('id', $sectionId)
                  ->where('page_id', $request->page_id)
                  ->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleActive(PageSection $section)
    {
        $section->update(['status' => !$section->status]);

        return response()->json([
            'success' => true,
            'status' => $section->status
        ]);
    }

    public function duplicate(PageSection $section)
    {
        $newSection = $section->replicate();
        $newSection->order = $section->page->sections()->count() + 1;
        $newSection->save();

        return response()->json([
            'success' => true,
            'section' => $newSection,
            'html' => $this->pageBuilder->renderSection($newSection)->render()
        ]);
    }

    private function getDefaultContent($type)
    {
        $defaults = [
            'hero' => [
                'title' => 'Welcome to Our Website',
                'subtitle' => 'Create amazing experiences with our page builder',
                'background_image' => '',
                'buttons' => [
                    [
                        'text' => 'Get Started',
                        'url' => '#',
                        'style' => 'btn-primary'
                    ],
                    [
                        'text' => 'Learn More',
                        'url' => '#',
                        'style' => 'btn-outline-light'
                    ]
                ]
            ],
            'features' => [
                'title' => 'Our Features',
                'description' => 'Discover what makes us different',
                'features' => [
                    [
                        'icon' => 'fas fa-rocket',
                        'title' => 'Fast Performance',
                        'description' => 'Lightning fast performance with reliable infrastructure.'
                    ],
                    [
                        'icon' => 'fas fa-shield-alt',
                        'title' => 'Secure',
                        'description' => 'Enterprise-grade security for your peace of mind.'
                    ],
                    [
                        'icon' => 'fas fa-cogs',
                        'title' => 'Customizable',
                        'description' => 'Fully customizable to fit your specific needs.'
                    ]
                ]
            ],
            'products' => [
                'title' => 'Featured Products',
                'description' => 'Check out our best selling products',
                'layout' => 'grid-4',
                'limit' => 8,
                'featured_only' => true,
                'product_ids' => []
            ],
            'contact' => [
                'title' => 'Contact Us',
                'description' => 'Get in touch with our team',
                'contact_info' => [
                    [
                        'icon' => 'fas fa-map-marker-alt',
                        'label' => 'Address',
                        'value' => '123 Business Street, City, State 12345'
                    ],
                    [
                        'icon' => 'fas fa-phone',
                        'label' => 'Phone',
                        'value' => '+1 (555) 123-4567'
                    ],
                    [
                        'icon' => 'fas fa-envelope',
                        'label' => 'Email',
                        'value' => 'hello@example.com'
                    ]
                ]
            ],
            'text' => [
                'title' => 'Your Content Here',
                'content' => '<p>This is a text section. You can edit this content to add your own text, images, and formatting.</p>',
                'alignment' => 'left'
            ],
            'cta' => [
                'title' => 'Ready to Get Started?',
                'subtitle' => 'Join thousands of satisfied customers today',
                'background_color' => '#007bff',
                'buttons' => [
                    [
                        'text' => 'Sign Up Now',
                        'url' => '#',
                        'style' => 'btn-light'
                    ]
                ]
            ]
        ];

        return $defaults[$type] ?? [];
    }
}
