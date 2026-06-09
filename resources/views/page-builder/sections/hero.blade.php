{{-- Hero Section --}}
@php
    $bgStyle = !empty($content['background_image'])
        ? 'background-image: url(' . asset($content['background_image']) . '); background-size: cover; background-position: center;'
        : '';
    $buttons = $content['buttons'] ?? [];
@endphp
<section class="pb-hero-section" style="{{ $bgStyle }} min-height: 420px; display:flex; align-items:center;">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 col-md-10 mx-auto text-center">
                @if(!empty($content['title']))
                    <h1 class="fw-bold display-5 mb-3">{{ $content['title'] }}</h1>
                @endif
                @if(!empty($content['subtitle']))
                    <p class="lead mb-4">{{ $content['subtitle'] }}</p>
                @endif
                @if(!empty($buttons))
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @foreach($buttons as $btn)
                            @if(!empty($btn['label']) && !empty($btn['url']))
                            <a href="{{ $btn['url'] }}" class="tf-btn {{ ($btn['style'] ?? '') === 'outline' ? 'btn-outline-primary' : '' }}">
                                <span>{{ $btn['label'] }}</span>
                            </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
