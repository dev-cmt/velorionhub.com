{{-- Testimonials Section --}}
@php
    $testimonials = $content['testimonials'] ?? [];
@endphp
<section class="pb-testimonials-section tf-sp-2 bg-light">
    <div class="container">
        @if(!empty($content['title']))
            <div class="text-center mb-5">
                <h2 class="fw-semibold">{{ $content['title'] }}</h2>
            </div>
        @endif
        @if(!empty($testimonials))
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    @if(!empty($testimonial['rating']))
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="icon-star{{ $i <= intval($testimonial['rating']) ? '' : '-empty' }} text-warning"></i>
                        @endfor
                    </div>
                    @endif
                    @if(!empty($testimonial['content']))
                        <p class="text-muted mb-3">&ldquo;{{ $testimonial['content'] }}&rdquo;</p>
                    @endif
                    <div class="d-flex align-items-center gap-3">
                        @if(!empty($testimonial['avatar']))
                            <img src="{{ asset($testimonial['avatar']) }}" alt="{{ $testimonial['name'] ?? '' }}"
                                 class="rounded-circle" width="44" height="44" style="object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:44px;height:44px;font-size:18px;">
                                {{ strtoupper(substr($testimonial['name'] ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="fw-semibold small">{{ $testimonial['name'] ?? '' }}</div>
                            @if(!empty($testimonial['role']))
                            <div class="text-muted" style="font-size:12px;">{{ $testimonial['role'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
