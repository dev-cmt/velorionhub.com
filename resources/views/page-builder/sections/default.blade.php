{{-- Default / Fallback section for unrecognised section types --}}
<section class="pb-section tf-sp-2" data-section-type="{{ $section->type ?? 'unknown' }}">
    <div class="container">
        @if(!empty($content['title']))
            <h2 class="mb-3">{{ $content['title'] }}</h2>
        @endif
        @if(!empty($content['content']))
            <div class="section-content">{!! $content['content'] !!}</div>
        @elseif(!empty($content['description']))
            <p>{{ $content['description'] }}</p>
        @endif
        @if(!empty($error))
            <!-- Section rendering error (visible in debug mode only) -->
            @if(config('app.debug'))
            <div class="alert alert-warning small mt-2">Section error: {{ $error }}</div>
            @endif
        @endif
    </div>
</section>
