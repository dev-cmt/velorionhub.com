{{-- Image Banner Section --}}
@php
    $alignment = $content['alignment'] ?? 'center';
    $alignClass = $alignment === 'center' ? 'text-center' : ($alignment === 'right' ? 'text-end' : 'text-start');
@endphp
<section class="pb-image-section tf-sp-2">
    <div class="container">
        <div class="{{ $alignClass }}">
            @if(!empty($content['image']))
                <img src="{{ asset($content['image']) }}"
                     alt="{{ $content['alt_text'] ?? '' }}"
                     class="img-fluid rounded"
                     style="max-width:100%;">
            @endif
            @if(!empty($content['caption']))
                <p class="mt-3 text-muted">{{ $content['caption'] }}</p>
            @endif
        </div>
    </div>
</section>
