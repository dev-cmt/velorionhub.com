<x-frontend-layout title="About Us" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-1 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">About</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    @if($page && $page->activeSections->isNotEmpty())
        {{-- Dynamic sections override static content when admin has configured them --}}
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @else
        {{-- Default static About Us content --}}
        <!-- Welcome -->
        <section class="tf-sp-2">
            <div class="container">
                <div class="flat-title-2">
                    <div class="box-title">
                        <h3 class="fw-semibold">Welcome to {{ $settings->company_name ?? config('app.name') }}</h3>
                        <p class="product-title">Blend contemporary designs with timeless elegance</p>
                    </div>
                    <div class="box-text">
                        At {{ $settings->company_name ?? config('app.name') }}, we offer meticulously curated collections that seamlessly combine modern <br
                            class="d-none d-xl-block">
                        aesthetics with classic sophistication. With more than 15 years of expertise, we serve <br
                            class="d-none d-xl-block">
                        fashion lovers who value quality, elegance, and adaptability.
                    </div>
                </div>
                <div class="parallaxie parallax-style" style='background: url("{{asset($filePath)}}/images/section/parallax-3.jpg")'>
                </div>
            </div>
        </section>
        <!-- /Welcome -->
    @endif
</x-frontend-layout>

