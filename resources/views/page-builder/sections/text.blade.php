{{-- Text Content Section --}}
@php
    $alignment = $content['alignment'] ?? 'left';
    $alignClass = $alignment === 'center' ? 'text-center' : ($alignment === 'right' ? 'text-end' : 'text-start');
@endphp
<section class="pb-text-section tf-sp-2">
    <div class="container">
        <div class="{{ $alignClass }}">
            @if(!empty($content['title']))
                <h2 class="fw-semibold mb-3">{{ $content['title'] }}</h2>
            @endif
            @if(!empty($content['content']))
                <div class="rich-text">{!! $content['content'] !!}</div>
            @endif
        </div>
    </div>
</section>
