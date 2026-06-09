{{-- Call To Action Section --}}
@php
    $bgColor = $content['background_color'] ?? '#212529';
    $buttons = $content['buttons'] ?? [];
@endphp
<section class="pb-cta-section tf-sp-2" style="background-color: {{ $bgColor }};">
    <div class="container">
        <div class="text-center py-4">
            @if(!empty($content['title']))
                <h2 class="fw-bold text-white mb-3">{{ $content['title'] }}</h2>
            @endif
            @if(!empty($content['subtitle']))
                <p class="text-white-50 mb-4">{{ $content['subtitle'] }}</p>
            @endif
            @if(!empty($buttons))
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @foreach($buttons as $btn)
                        @if(!empty($btn['label']) && !empty($btn['url']))
                        <a href="{{ $btn['url'] }}" class="tf-btn {{ ($btn['style'] ?? '') === 'outline' ? 'btn-outline-light' : '' }}">
                            <span>{{ $btn['label'] }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
