<x-backend-layout title="Setting">
    @push('css')

    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Info
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" id="company_name"
                                    value="{{ $settings ? $settings->company_name : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="alert_email" class="form-label">Alert Email</label>
                                <input type="email" name="alert_email" class="form-control" id="alert_email"
                                    value="{{ $settings ? $settings->alert_email : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ $settings ? $settings->email : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email2" class="form-label">Email 2</label>
                                <input type="email" name="email2" class="form-control" id="email2"
                                    value="{{ $settings ? $settings->email2 : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="number" name="phone" class="form-control" id="phone"
                                    value="{{ $settings ? $settings->phone : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="phone2" class="form-label">Phone 2</label>
                                <input type="number" name="phone2" class="form-control" id="phone2"
                                    value="{{ $settings ? $settings->phone2 : '' }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="address">Address</label>
                                <textarea name="address" id="address" rows="2"
                                    class="form-control">{{ $settings ? $settings->address : '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="map_url">Map Url</label>
                                <textarea name="map_url" id="map_url" rows="2"
                                    class="form-control">{{ $settings ? $settings->map_url : '' }}</textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" id="description" rows="2"
                                    class="form-control">{{ $settings ? $settings->description : '' }}</textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label" for="copyright_text">Copyright Text</label>
                                <textarea name="copyright_text" id="copyright_text" rows="1"
                                    class="form-control">{{ $settings ? $settings->copyright_text : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Frontend Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_loading" value="0">
                                    <input type="checkbox" name="is_loading" id="is_loading" value="1"
                                        class="form-check-input" role="switch" {{ $settings ? ($settings->is_loading ? 'checked' : '') : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_loading">Enable
                                        Pre-Loader</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_slider" value="0">
                                    <input type="checkbox" name="is_slider" id="is_slider" value="1"
                                        class="form-check-input" role="switch" {{ $settings ? ($settings->is_slider ? 'checked' : '') : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_slider">Enable Frontend
                                        Slider</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">Social Links</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text" name="facebook" class="form-control" id="facebook"
                                            value="{{ $settings ? $settings->facebook : '' }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="twitter" class="form-label">Twitter</label>
                                        <input type="text" name="twitter" class="form-control" id="twitter"
                                            value="{{ $settings ? $settings->twitter : '' }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="text" name="instagram" class="form-control" id="instagram"
                                            value="{{ $settings ? $settings->instagram : '' }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="linkedin" class="form-label">Linkedin</label>
                                        <input type="text" name="linkedin" class="form-control" id="linkedin"
                                            value="{{ $settings ? $settings->linkedin : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">Logo</div>
                            </div>
                            <div class="card-body">
                                @if ($settings)
                                    @if ($settings->logo)
                                        <img class="mb-2" src="{{ asset($settings->logo) }}" alt="{{ $settings->logo }}"
                                            width="50">
                                    @endif
                                @endif
                                <input type="file" name="logo" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">Favicon </div>
                            </div>
                            <div class="card-body">
                                @if ($settings)
                                    @if ($settings->favicon)
                                        <img class="mb-2" src="{{ asset($settings->favicon) }}" alt="{{ $settings->favicon }}"
                                            width="50">
                                    @endif
                                @endif
                                <input type="file" name="favicon" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">Logo Light </div>
                            </div>
                            <div class="card-body">
                                @if ($settings)
                                    @if ($settings->logo_light)
                                        <img class="mb-2" src="{{ asset($settings->logo_light) }}"
                                            alt="{{ $settings->logo_light }}" width="50">
                                    @endif
                                @endif
                                <input type="file" name="logo_light" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">Logo Dark </div>
                            </div>
                            <div class="card-body">
                                @if ($settings)
                                    @if ($settings->logo_dark)
                                        <img class="mb-2" src="{{ asset($settings->logo_dark) }}"
                                            alt="{{ $settings->logo_dark }}" width="50">
                                    @endif
                                @endif
                                <input type="file" name="logo_dark" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-0">
                    <button type="submit" class="btn btn-success w-100">Update</button>
                </div>
            </div>
        </div>
    </form>

    @push('js')

    @endpush
</x-backend-layout>
