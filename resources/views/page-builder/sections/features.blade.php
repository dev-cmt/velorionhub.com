{{-- Features Grid Section --}}
@php
    $features = $content['features'] ?? [];
@endphp
<section class="pb-features-section tf-sp-2 bg-light">
    <div class="container">
        @if(!empty($content['title']))
            <div class="text-center mb-5">
                <h2 class="fw-semibold">{{ $content['title'] }}</h2>
                @if(!empty($content['description']))
                    <p class="text-muted mt-2">{{ $content['description'] }}</p>
                @endif
            </div>
        @endif
        @if(!empty($features))
        <div class="row g-4">
            @foreach($features as $feature)
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded shadow-sm h-100 text-center">
                    @if(!empty($feature['icon']))
                        <div class="mb-3">
                            <i class="{{ $feature['icon'] }} fs-2 text-primary"></i>
                        </div>
                    @endif
                    @if(!empty($feature['title']))
                        <h5 class="fw-semibold mb-2">{{ $feature['title'] }}</h5>
                    @endif
                    @if(!empty($feature['description']))
                        <p class="text-muted small mb-0">{{ $feature['description'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
