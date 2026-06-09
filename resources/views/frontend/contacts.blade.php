<x-frontend-layout title="Contact Us" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="{{ route('home') }}" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Contact</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    @if($page && $page->activeSections->isNotEmpty())
        {{-- Render dynamic sections built by admin --}}
        @foreach($page->activeSections as $section)
            {!! app(\App\Services\PageBuilder::class)->renderSection($section) !!}
        @endforeach
    @else
        <!-- Contact (static fallback) -->
        <section class="tf-sp-2">
            <div class="container">
                <div class="wg-map">
                    <iframe
                        src="{{ $settings->map_embed ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11678.740279919208!2d-75.53672684990242!3d39.167930537914174!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c77b533177974f%3A0xd017ee22f8759803!2sWesley%20College%20%2F%20DSU!5e0!3m2!1sen!2s!4v1741056536407!5m2!1sen!2s' }}"
                        height="585" style="border-radius:8px; width: 100%;" allowfullscreen=""
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <div class="bottom">
                        <div class="contact-wrap">
                            <div class="box-title">
                                <h5 class="fw-semibold">Get A Quote</h5>
                                <p class="body-text-3">
                                    Fill up the form and our Team will get back to you within 24 hours.
                                </p>
                            </div>
                            <form class="form-contact def" method="POST" action="{{ \Illuminate\Support\Facades\Route::has('contact.send') ? route('contact.send') : '#' }}">
                                @csrf
                                <fieldset>
                                    <label>Name</label>
                                    <input type="text" name="name" required>
                                </fieldset>
                                <fieldset>
                                    <label>Subject</label>
                                    <input type="text" name="subject" required>
                                </fieldset>
                                <fieldset class="d-flex flex-column">
                                    <label>Your message</label>
                                    <textarea name="message" style="height: 170px;" required></textarea>
                                </fieldset>
                                <div class="box-btn-submit">
                                    <button type="submit" class="tf-btn text-white w-100">
                                        Send message
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="contact-info">
                            <h5 class="fw-semibold">Contact Information</h5>
                            <ul class="info-list">
                                <li>
                                    <span class="icon"><i class="icon-location"></i></span>
                                    <a href="{{ $settings->address ? 'https://www.google.com/maps?q=' . urlencode($settings->address) : '#' }}"
                                       class="link" target="_blank">
                                        {{ $settings->address ?? '8500 Lorem Street, Chicago, IL 55030' }}
                                    </a>
                                </li>
                                <li>
                                    <span class="icon"><i class="icon-phone"></i></span>
                                    <a href="tel:{{ $settings->phone ?? '' }}" class="product-title fw-semibold link">
                                        <span>{{ $settings->phone ?? '+8(800) 123 4567' }}</span>
                                    </a>
                                </li>
                                <li>
                                    <span class="icon"><i class="icon-direction"></i></span>
                                    <a href="mailto:{{ $settings->email ?? '' }}" class="link">
                                        <span>{{ $settings->email ?? 'support@example.com' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Contact -->
    @endif
</x-frontend-layout>
