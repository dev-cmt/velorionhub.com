<x-frontend-layout :title="$page->title ?? 'Page'" :breadcrumbs="$breadcrumbs ?? ''" :seotags="$seotags ?? ''">
    {{-- Page Breadcrumbs --}}
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">Home</a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">{{ $page->title ?? 'Page' }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Dynamic Sections from Page Builder --}}
    @if($page && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @else
        {{-- Fallback: simple page content block --}}
        <section class="tf-sp-2">
            <div class="container">
                <div class="text-center py-5">
                    <h2 class="fw-semibold mb-3">{{ $page->title ?? 'Page' }}</h2>
                    @if($page && $page->meta_description)
                        <p class="text-muted">{{ $page->meta_description }}</p>
                    @else
                        <p class="text-muted">This page has no content yet. Add sections using the Page Builder.</p>
                    @endif
                </div>
            </div>
        </section>
    @endif
</x-frontend-layout>
