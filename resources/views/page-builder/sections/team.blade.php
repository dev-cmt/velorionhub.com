{{-- Team Members Section --}}
@php
    $members = $content['members'] ?? [];
@endphp
<section class="pb-team-section tf-sp-2">
    <div class="container">
        @if(!empty($content['title']))
            <div class="text-center mb-5">
                <h2 class="fw-semibold">{{ $content['title'] }}</h2>
                @if(!empty($content['description']))
                    <p class="text-muted mt-2">{{ $content['description'] }}</p>
                @endif
            </div>
        @endif
        @if(!empty($members))
        <div class="row g-4">
            @foreach($members as $member)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="text-center p-4 bg-light rounded">
                    @if(!empty($member['photo']))
                        <img src="{{ asset($member['photo']) }}" alt="{{ $member['name'] ?? '' }}"
                             class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3"
                             style="width:100px;height:100px;font-size:32px;">
                            {{ strtoupper(substr($member['name'] ?? 'T', 0, 1)) }}
                        </div>
                    @endif
                    <h6 class="fw-semibold mb-1">{{ $member['name'] ?? '' }}</h6>
                    @if(!empty($member['role']))
                        <p class="text-muted small mb-2">{{ $member['role'] }}</p>
                    @endif
                    @if(!empty($member['bio']))
                        <p class="text-muted" style="font-size:13px;">{{ $member['bio'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
