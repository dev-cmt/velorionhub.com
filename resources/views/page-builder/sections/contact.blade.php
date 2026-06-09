{{-- Contact Form Section --}}
@php
    $contactInfo = $content['contact_info'] ?? [];
    $globalSettings = app('settings') ?? null;
@endphp
<section class="pb-contact-section tf-sp-2">
    <div class="container">
        @if(!empty($content['title']))
            <div class="text-center mb-5">
                <h2 class="fw-semibold">{{ $content['title'] }}</h2>
                @if(!empty($content['description']))
                    <p class="text-muted mt-2">{{ $content['description'] }}</p>
                @endif
            </div>
        @endif
        <div class="row g-5">
            <div class="col-lg-7">
                <form class="form-contact def" method="POST" action="{{ \Illuminate\Support\Facades\Route::has('contact.send') ? route('contact.send') : '#' }}">
                    @csrf
                    <fieldset>
                        <label>Name</label>
                        <input type="text" name="name" required>
                    </fieldset>
                    <fieldset>
                        <label>Email</label>
                        <input type="email" name="email" required>
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
            <div class="col-lg-5">
                <div class="contact-info">
                    <h5 class="fw-semibold mb-4">Contact Information</h5>
                    <ul class="info-list">
                        @if(!empty($contactInfo))
                            @foreach($contactInfo as $info)
                                @if(!empty($info['value']))
                                <li>
                                    <span class="icon"><i class="{{ $info['icon'] ?? 'icon-location' }}"></i></span>
                                    <span>{{ $info['value'] }}</span>
                                </li>
                                @endif
                            @endforeach
                        @else
                            {{-- Fall back to global site settings --}}
                            @if($globalSettings && $globalSettings->address)
                            <li>
                                <span class="icon"><i class="icon-location"></i></span>
                                <span>{{ $globalSettings->address }}</span>
                            </li>
                            @endif
                            @if($globalSettings && $globalSettings->phone)
                            <li>
                                <span class="icon"><i class="icon-phone"></i></span>
                                <a href="tel:{{ $globalSettings->phone }}" class="product-title fw-semibold link">
                                    <span>{{ $globalSettings->phone }}</span>
                                </a>
                            </li>
                            @endif
                            @if($globalSettings && $globalSettings->email)
                            <li>
                                <span class="icon"><i class="icon-direction"></i></span>
                                <a href="mailto:{{ $globalSettings->email }}" class="link">
                                    <span>{{ $globalSettings->email }}</span>
                                </a>
                            </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
